<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Hateoas\Configuration\Annotation as Hateoas;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 *
 * @Hateoas\Relation(
 *     "self",
 *     href = @Hateoas\Route(
 *          "app_product_show",
 *          parameters = { "id" = "expr(object.getId())" },
 *          absolute = true
 *      ),
 *      exclusion = @Hateoas\Exclusion(groups={"list", "detail"})
 * )
 *
 * @Hateoas\Relation(
 *     "list",
 *     href = @Hateoas\Route(
 *          "app_products_list",
 *          absolute = true
 *      ),
 *      exclusion = @Hateoas\Exclusion(groups={"detail"})
 * )
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @Serializer\Expose()
     * @Serializer\Groups({"list", "detail"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Serializer\Expose()
     * @Serializer\Groups({"list", "detail"})
     */
    private $name;

    /**
     * @ORM\Column(type="float")
     *
     * @Serializer\Expose()
     * @Serializer\Groups({"list", "detail"})
     */
    private $price;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @Serializer\Expose()
     * @Serializer\Groups({"detail"})
     */
    private $description;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
