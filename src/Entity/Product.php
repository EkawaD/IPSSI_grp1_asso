<?php

namespace App\Entity;

use App\Entity\CartProduct;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

     /**
     *
     * @ORM\Column(type="string", length=64, nullable=false)
     * @Assert\File(
     *  mimeTypes={"image/png", "image/jpeg"},
     *  mimeTypesMessage =" Seuls les types {{ types }} sont acceptés."
     * )
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity=CartProduct::class, mappedBy="product")
     */
    private $cartProducts;


    public function __construct()
    {
        $this->carts = new ArrayCollection();
        $this->cartProducts = new ArrayCollection();
    }


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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

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

    /**
     * Get the value of image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set the value of image
     */
    public function setImage($image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection|Cart[]
     */
    public function getCarts(): Collection
    {
        return $this->carts;
    }


    /**
     * @ORM\PostRemove
     */
    public function deleteImage()
    {
        if(file_exists(__DIR__.'/../../public/img/upload/'.$this->image)) {
            unlink(__DIR__.'/../../public/img/upload/'.$this->image);
        }
        return true;
    }

    /**
     * @return Collection|cartProdcut[]
     */
    public function getCartProducts(): Collection
    {
        return $this->cartProdcuts;
    }

    public function addCartProduct(CartProduct $cartProdcut): self
    {
        if (!$this->cartProdcuts->contains($cartProdcut)) {
            $this->cartProdcuts[] = $cartProdcut;
            $cartProdcut->setProduct($this);
        }

        return $this;
    }

    public function removeCartProdcut(CartProduct $cartProdcut): self
    {
        if ($this->cartProdcuts->removeElement($cartProdcut)) {
            // set the owning side to null (unless already changed)
            if ($cartProdcut->getProduct() === $this) {
                $cartProdcut->setProduct(null);
            }
        }

        return $this;
    }


}
