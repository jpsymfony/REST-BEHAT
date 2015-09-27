<?php

namespace App\ApiBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationListInterface;


use App\CoreBundle\Entity\Category;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation as Doc;

use FOS\RestBundle\Request\ParamFetcherInterface;


/**
 *  @Route("/categories") 
 */
class CategoryController extends FOSRestController
{
    /**
     * @Rest\Get("/", name="app_api_categories")
     * @Rest\QueryParam(
     *     name="keyword",
     *     requirements="[a-zA-Z0-9]+",
     *     nullable=true,
     *     description="The keyword to search for."
     * )
     * @Rest\QueryParam(
     *     name="order",
     *     requirements="asc|desc",
     *     default="asc",
     *     description="Sort order (asc or desc)."
     * )
     * @Rest\QueryParam(
     *     name="limit",
     *     requirements="\d+",
     *     default="20",
     *     description="Max number of categories per page."
     * )
     * @Rest\QueryParam(
     *     name="offset",
     *     requirements="\d+",
     *     default="0",
     * description="The pagination offset."
     * )

     * @Doc\ApiDoc(
     *     section="Categories",
     *     resource=true,
     *     description="Get the list of all categories.",
     *     statusCodes={
     *          200="Returned when successful",
     *     }
     * )
     */
    public function getCategoriesAction(ParamFetcherInterface $paramFetcher)
    {
        $repository =  $this->get('app_core.repository.category');
        
        $categories = $repository->search(
            $paramFetcher->get('keyword'), $paramFetcher->get('order'), $paramFetcher->get('limit'),
            $paramFetcher->get('offset')
        );
        
        return new \App\ApiBundle\Representation\Categories($categories);
    }
    
    /**
     * @Rest\Get(
     *    path = "/{id}",
     *    name="app_api_category",
     *    requirements={"id"="\d+"}
     * )
     *
     * @Doc\ApiDoc(
     *     section="Categories",
     *     resource=true,
     *     description="Get one category.",
     *     requirements={
     *          {
     *              "name"="id",
     *              "dataType"="integer",
     *              "requirement"="\d+",
     *              "description"="The genre unique identifier."
     *          }
     *      },
     *      statusCodes={
     *          200="Returned when successful",
     *      }
     * )
     */
    public function getCategoryAction(Category $category)
    {
//        return $this->render(
//            'AppApiBundle:Category:category.json.twig',
//            ['category' => $category]
//        );
        return $category;
    }
    
/**
     * @Rest\Post("/", name="app_api_new_category")
     *
     * @Doc\ApiDoc(
     *      section="Categories",
     *      description="Creates a new category.",
     *      statusCodes={
     *          201="Returned if category has been successfully created",
     *          400="Returned if errors",
     *          500="Returned if server error"
     *      }
     * )
     */
    public function postCategoryAction(Request $request)
    {
        $data = $request->request->get('category');

        if (empty($data['title']) || empty($data['slug'])) {
            return $this->view([ 'error' => 'Missing title or slug.'], 400);
        }

        $category = new Category();
        $category->setTitle($data['title']);
        $category->setSlug($data['slug']);

        $em = $this->getDoctrine()->getManager();
        $em->persist($category);
        $em->flush();
        $location = $this->generateUrl('app_api_category', [ 'id' => $category->getId() ], true);

        return $this->view('', 201, [ 'Location' => $location]);
    }
    
    
    /**
     * @Rest\Put(
     *     path = "/{id}",
     *     name = " app_api_edit_category",
     *     requirements = {"id"="\d+"}
     * )
     * 
     * @Doc\ApiDoc(
     *      section="Categories",
     *      description="Edit an existing genre.",
     *      statusCodes={
     *          201="Returned if genre has been successfully edited",
     *          400="Returned if errors",
     *          500="Returned if server error"
     *      },
     *      requirements={
     *          {
     *              "name"="id",
     *              "dataType"="integer",
     *              "requirement"="\d+",
     *              "description"="The category unique identifier."
     *          }
     *      },
     * )
     */
    public function putCategoryAction(Category $category, Request $request)
    {
        $data = $request->request->get('category');
        if (empty($data['title']) || empty($data['slug'])) {
            return $this->view(
                [ 'error' => 'Missing title or slug parameters.'],
                Response::HTTP_BAD_REQUEST
            );
        }
        
        $category->setSlug($data['slug']);
        $category->setTitle($data['title']);
        $em = $this->getDoctrine()->getManager();
        $em->flush($category);
        return $this->view('', Response::HTTP_NO_CONTENT);
    }
    
    /**
     * @Rest\Delete(
     *     path = "/{id}",
     *     name = "app_api_delete_genre",
     *     requirements = {"id"="\d+"}
     * )
     * 
     * @Rest\View(statusCode=204)
     *
     * @Doc\ApiDoc(
     *      section="Categories",
     *      description="Delete an existing genre.",
     *      statusCodes={
     *          201="Returned if genre has been successfully deleted",
     *          400="Returned if genre does not exist",
     *          500="Returned if server error"
     *      },
     *      requirements={
     *          {
     *              "name"="id",
     *              "dataType"="integer",
     *              "requirement"="\d+",
     *              "description"="The genre unique identifier."
     *          }
     *      },
     * )
     */
    public function deleteCategoryAction(Category $category)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($genre);
        $em->flush();
        
        return $this->view('', Response::HTTP_NO_CONTENT);
    }
}