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
        $art1 = new Article();
        $art1->setTitle('Article 1');
        $art1->setSlug('article-1');
        $art1->setDescription('description');
        $art1->setCategory($category1);

        $category2 = $manager->getRepository('AppCoreBundle:Category')->findOneBySlug('crypto');
        $art2 = new Article();
        $art2->setTitle('Article 2');
        $art2->setSlug('article-2');
        $art2->setDescription('description');
        $art2->setCategory($category2);

        $category3 = $manager->getRepository('AppCoreBundle:Category')->findOneBySlug('bad-practices');
        $art3 = new Article();
        $art3->setTitle('Article 3');
        $art3->setSlug('article-3');
        $art3->setDescription('description');
        $art3->setCategory($category3);

        $category4 = $manager->getRepository('AppCoreBundle:Category')->findOneBySlug('design-patterns');
        $art4 = new Article();
        $art4->setTitle('Article 4');
        $art4->setSlug('article-4');
        $art4->setDescription('description');
        $art4->setCategory($category4);

        $manager->persist($art1);
        $manager->persist($art2);
        $manager->persist($art3);
        $manager->persist($art4);
        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}
