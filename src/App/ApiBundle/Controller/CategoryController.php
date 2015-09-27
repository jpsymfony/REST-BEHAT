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

use App\CoreBundle\Entity\Category;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation as Doc;
use Symfony\Component\Validator\ConstraintViolationListInterface;


/**
 *  @Route("/categories") 
 */
class CategoryController extends FOSRestController
{
    /**
     * @Rest\Get("/", name="app_api_categories")
     * @Doc\ApiDoc(
     *     section="Categories",
     *     resource=true,
     *     description="Get the list of all categories.",
     *     statusCodes={
     *          200="Returned when successful",
     *     }
     * )
     */
    public function getCategoriesAction()
    {
//        return $this->render('AppApiBundle:Category:categories.json.twig', [
//            'categories' => $this->get('app_core.repository.category')->getCategories(),]); 
        
        return $this->get('app_core.repository.category')->getCategories();
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
    * @Route(
    *   path = "/{id}",
    *   name = "app_api_delete_category",
    *   defaults = {"_format"="json"},
    *   requirements = {"id"="\d+"})
    *   @Method("DELETE")
    */
    public function deleteCategoryAction(Category $category)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();
        return new JsonResponse('', Response::HTTP_NO_CONTENT);
    }
    
    private function decodeJsonBody(Request $request)
    {
        $parameters = json_decode($request->getContent(), true);
        $request->request = new ParameterBag($parameters);
    }
}