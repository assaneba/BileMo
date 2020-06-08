<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\Validator\Constraints as Assert;
use Hateoas\Configuration\Annotation as Hateoas;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 *
 * @Serializer\ExclusionPolicy("All")
 *
 * @Hateoas\Relation(
 *      "self",
 *      href = @Hateoas\Route(
 *          "product_show",
 *          parameters = { "id" = "expr(object.getId())" },
 *          absolute = true,
 *      ),
 *  exclusion = @Hateoas\Exclusion(groups={"list"})
 * )
 *
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"list", "detail"})
     *
     * @Serializer\Expose
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"list", "detail"})
     * @Assert\NotBlank()
     * @Assert\Length(max="25", maxMessage="Too much caracters for model name")
     *
     * @Serializer\Expose
     */
    private $model;

    /**
     * @ORM\Column(type="string", length=255)
     * @Serializer\Groups({"list", "detail"})
     * @Assert\NotBlank()
     * @Assert\Length(max="25", maxMessage="Too much caracters for brand name")
     *
     * @Serializer\Expose
     */
    private $brand;

    /**
     * @ORM\Column(type="integer")
     * @Serializer\Groups({"list", "detail"})
     * @Assert\NotBlank()
     * @Assert\Range(min="0")
     *
     * @Serializer\Expose
     */
    private $price;

    /**
     * @ORM\Column(type="text")
     * @Serializer\Groups({"detail"})
     * @Assert\NotBlank()
     */
    private $description;

    /**
     * @ORM\Column(type="date")
     * @Serializer\Groups({"detail"})
     *
     * @Serializer\Expose
     */
    private $releaseDate;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="products")
     * @ORM\JoinColumn(nullable=false)
     * @ORM\JoinTable(name="product_user")
     */
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $this->model = $model;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

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

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(\DateTimeInterface $releaseDate): self
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
        }

        return $this;
    }

}
