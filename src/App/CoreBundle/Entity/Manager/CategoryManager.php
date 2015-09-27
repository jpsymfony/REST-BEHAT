<?php

namespace App\CoreBundle\Entity\Manager;

use Cocur\Slugify\Slugify;
use App\CoreBundle\Entity\Category;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryManager
{
    private $slugger;

    public function __construct(Slugify $slugger, ObjectManager $em) {
        $this->slugger = $slugger;
        $this->em = $em;
    }

    public function update(Category $category, Category $apiCategory)
    {
        if (!$apiCategory->getSlug()) {
            $apiCategory->setSlug($this->slugger->slugify($category->getTitle()));
        }

        $category->update($apiCategory);

        $this->em->flush();
    }

    public function save(Category $category)
    {
        if (!$category->getSlug()) {
            $category->setSlug($this->slugger->slugify($category->getTitle()));
        }

        $this->em->persist($category);
        $this->em->flush();
    }
}