<?php

namespace Smoovio\Bundle\PortalBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use App\CoreBundle\Entity\Category;

class LoadCategoryData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $cat1 = new Category();
        $cat1->setTitle('Insolite');
        $cat1->setSlug('insolite');

        $cat2 = new Category();
        $cat2->setTitle('Crypto');
        $cat2->setSlug('crypto');

        $cat3 = new Category();
        $cat3->setTitle('Bad practices');
        $cat3->setSlug('bad-practices');

        $cat4 = new Category();
        $cat4->setTitle('Design patterns');
        $cat4->setSlug('design-patterns');

        $manager->persist($cat1);
        $manager->persist($cat2);
        $manager->persist($cat3);
        $manager->persist($cat4);
        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}
