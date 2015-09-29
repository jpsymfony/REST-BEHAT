<?php

namespace Smoovio\Bundle\PortalBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use App\CoreBundle\Entity\Article;

class LoadArticleData extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $category1 = $manager->getRepository('AppCoreBundle:Category')->findOneBySlug('insolite');
        $category2 = $manager->getRepository('AppCoreBundle:Category')->findOneBySlug('crypto');
        $category3 = $manager->getRepository('AppCoreBundle:Category')->findOneBySlug('bad-practices');
        $category4 = $manager->getRepository('AppCoreBundle:Category')->findOneBySlug('design-patterns');

        $categories = array($category1, $category2, $category3, $category4);

        foreach ($categories as $category) {
            for ($i = 1; $i < 30; $i++) {
                $art = new Article();
                $art->setTitle('Article ' . $i . ' ' . $category->getTitle());
                $art->setSlug('article-' . $i . ' ' . $category->getSlug());
                $art->setDescription('description article ' . $i . ' ' .$category->getTitle());
                $art->setCategory($category);
                $manager->persist($art);
            }
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}