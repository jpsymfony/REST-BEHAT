<?php

namespace App\CoreBundle\Entity\Manager;

use App\CoreBundle\Entity\Category;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryManager
{
    public function __construct(ObjectManager $em) {
        $this->em = $em;
    }

    public function update(Category $category, Category $apiCategory)
    {
        $category->update($apiCategory);

        $this->em->flush();
    }

    public function save(Category $category)
    {
        $this->em->persist($category);
        $this->em->flush();
    }
}