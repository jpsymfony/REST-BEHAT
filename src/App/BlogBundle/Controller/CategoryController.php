<?php

namespace App\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Category controller.
 *
 * @Route("/category")
 */
class CategoryController extends Controller
{

    /**
     * Lists all Category entities.
     *
     * @Route("/", name="categories")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository('AppCoreBundle:Category')->findAll();

        return array(
            'categories' => $entities,
        );
    }

    /**
     * Finds and displays a Category entity.
     *
     * @Route("/{id}", name="category_show")
     * @Method("GET")
     * @ParamConverter("post", class="AppCoreBundle:Category")
     * @Template()
     */
    public function showAction(\App\CoreBundle\Entity\Category $category)
    {
        $articles = $this->getArticleRepository()->findByCategory($category, ['title' => 'asc']);

        return $this->render(
            'AppBlogBundle:Category:show.html.twig',
            ['articles' => $articles, 'category' => $category]
        );
    }

    
    /**
     * @return ObjectRepository
     */
    private function getArticleRepository()
    {
        return $this->get('app_core.repository.article');
    }
}
