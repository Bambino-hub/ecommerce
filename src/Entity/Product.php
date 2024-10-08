<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints\Image;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[Vich\Uploadable]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $discription = null;

    #[ORM\Column]
    private ?int $price = null;

    /**
     * @var Collection<int, SubCategory>
     */
    #[ORM\ManyToMany(targetEntity: SubCategory::class, inversedBy: 'products')]
    private Collection $subcategories;


    #[Vich\UploadableField(mapping: 'products', fileNameProperty: 'image')]
    private ?File $imagefile = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Image()]
    private ?string $image = null;

    #[ORM\Column]
    private ?int $stock = null;

    /**
     * @var Collection<int, ProductHIstry>
     */
    #[ORM\OneToMany(targetEntity: ProductHIstry::class, mappedBy: 'product')]
    private Collection $productHIstries;

    /**
     * @var Collection<int, OrderProduct>
     */
    #[ORM\OneToMany(targetEntity: OrderProduct::class, mappedBy: 'product')]
    private Collection $orderProducts;

    public function __construct()
    {
        $this->subcategories = new ArrayCollection();
        $this->productHIstries = new ArrayCollection();
        $this->orderProducts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDiscription(): ?string
    {
        return $this->discription;
    }

    public function setDiscription(?string $discription): static
    {
        $this->discription = $discription;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection<int, SubCategory>
     */
    public function getSubcategories(): Collection
    {
        return $this->subcategories;
    }

    public function addSubcategory(SubCategory $subcategory): static
    {
        if (!$this->subcategories->contains($subcategory)) {
            $this->subcategories->add($subcategory);
        }

        return $this;
    }

    public function removeSubcategory(SubCategory $subcategory): static
    {
        $this->subcategories->removeElement($subcategory);

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get the value of imagefile
     */
    public function getImagefile(): ?File
    {
        return $this->imagefile;
    }

    /**
     * Set the value of imagefile
     */
    public function setImagefile(?File $imagefile): self
    {
        $this->imagefile = $imagefile;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * @return Collection<int, ProductHIstry>
     */
    public function getProductHIstries(): Collection
    {
        return $this->productHIstries;
    }

    public function addProductHIstry(ProductHIstry $productHIstry): static
    {
        if (!$this->productHIstries->contains($productHIstry)) {
            $this->productHIstries->add($productHIstry);
            $productHIstry->setProduct($this);
        }

        return $this;
    }

    public function removeProductHIstry(ProductHIstry $productHIstry): static
    {
        if ($this->productHIstries->removeElement($productHIstry)) {
            // set the owning side to null (unless already changed)
            if ($productHIstry->getProduct() === $this) {
                $productHIstry->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, OrderProduct>
     */
    public function getOrderProducts(): Collection
    {
        return $this->orderProducts;
    }

    public function addOrderProduct(OrderProduct $orderProduct): static
    {
        if (!$this->orderProducts->contains($orderProduct)) {
            $this->orderProducts->add($orderProduct);
            $orderProduct->setProduct($this);
        }

        return $this;
    }

    public function removeOrderProduct(OrderProduct $orderProduct): static
    {
        if ($this->orderProducts->removeElement($orderProduct)) {
            // set the owning side to null (unless already changed)
            if ($orderProduct->getProduct() === $this) {
                $orderProduct->setProduct(null);
            }
        }

        return $this;
    }
}
