<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\ProductTagRepository")]
#[ORM\Table(name: "mia_product_tag")]
class ProductTag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "bigint")]
    private $id;

    #[ORM\ManyToOne(targetEntity: Tag::class)]
    #[ORM\JoinColumn(name: "tag_id", referencedColumnName: "id", nullable: false)]
    private $tagId;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Tag
     */
    public function getTagId(): Tag
    {
        return $this->tagId;
    }

    /**
     * @param Tag $tagId
     * @return $this
     */
    public function setTagId(Tag $tagId): ProductTag
    {
        $this->tagId = $tagId;

        return $this;
    }
}
