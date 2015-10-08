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
        $cat1 = new Category('Insolite');
        $cat2 = new Category('Crypto');
        $cat3 = new Category('Bad practices');
        $cat4 = new Category('Design patterns');

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
