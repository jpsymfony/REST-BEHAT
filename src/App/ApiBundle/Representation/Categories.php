<?php

namespace App\ApiBundle\Representation;

use JMS\Serializer\Annotation as Serializer;


/**
 * @Serializer\XmlRoot("categories")
 */
class Categories
{
    /**
     * @Serializer\Expose
     * @Serializer\Type("array<App\CoreBundle\Entity\Category>")
     * @Serializer\XmlList(inline=true, entry="category")
     * @Serializer\SerializedName("categories")
     */
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }
}
