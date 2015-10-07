<?php

namespace App\BlogBundle\Controller;

use App\CoreBundle\Entity\Category;
use Doctrine\Common\Persistence\ObjectRepository;
use App\CoreBundle\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Category controller.
 *
 * @Route("/article")
 */
class ArticleController extends Controller
{
    /**
     * Finds and displays a Category entity.
     *
     * @param Article $article
     * @Route("/{id}/{slug}", name="article_detail")
     * @Method("GET")
     * @ParamConverter("article", class="AppCoreBundle:Article")
     * @Template()
     */
    public function detailAction(Article $article)
    {
        return array('article' => $article);
    }

    /**
     *
     * @param Category $category
     * @Route("/category/{slug}/article", name="articles_by_category")
     * @Method("GET")
     * @ParamConverter("category", class="AppCoreBundle:Category")
     * @Template()
     */
    public function listByCategoryAction(Category $category)
    {
        $articles = $this->getArticleRepository()->findByCategory($category, ['title' => 'asc']);

        return $this->render(
            'AppBlogBundle:Article:listByCategory.html.twig',
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
