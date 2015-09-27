<?php

namespace App\ApiBundle\Representation;

use JMS\Serializer\Annotation as Serializer;
use Pagerfanta\Pagerfanta;

/**
 * @Serializer\XmlRoot("categories")
 */
class Categories implements RepresentationInterface
{
    /**
     * @Serializer\Expose
     * @Serializer\XmlKeyValuePairs
     */
    private $meta;

    /**
     * @Serializer\Expose
     * @Serializer\Type("array<App\CoreBundle\Entity\Category>")
     * @Serializer\XmlList(inline=true, entry="category")
     * @Serializer\SerializedName("categories")
     */
    private $data;

    public function __construct(Pagerfanta $data)
    {
        $this->data = $data;

        $this->addMeta('limit', $data->getMaxPerPage());
        $this->addMeta('current_items', count($data->getCurrentPageResults()));
        $this->addMeta('total_items', $data->getNbResults());
        $this->addMeta('offset', $data->getCurrentPageOffsetStart());
    }

    public function addMeta($key, $value)
    {
        $this->meta[$key] = $value;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getMeta($key)
    {
        return $this->meta[$key];
    }
}
