<?php

namespace AppBundle\Entity;

use AppBundle\ValueObject\Collection;
use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

/**
 * Cart
 *
 * @ORM\Table(name="cart")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CartRepository")
 */
class Cart
{
    use ORMBehaviors\SoftDeletable\SoftDeletable;
    use ORMBehaviors\Timestampable\Timestampable;
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \AppBundle\Entity\User
     *
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="User", inversedBy="carts")
     * @Doctrine\ORM\Mapping\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;

    /**
     * @var \AppBundle\Entity\Product[]
     *
     * @Doctrine\ORM\Mapping\ManyToMany(targetEntity="Product")
     * @Doctrine\ORM\Mapping\JoinTable(name="cart_products")
     */
    private $products;

    /**
     * @var \DateTime
     *
     * @Doctrine\ORM\Mapping\Column(type="datetime", nullable=true, options={"default":null})
     */
    private $submittedAt;

    /**
     * Cart constructor.
     */
    public function __construct()
    {

        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return \DateTime|null
     */
    public function getSubmittedAt(): ?\DateTime
    {
        return $this->submittedAt;
    }

    /**
     * @param \DateTime $submittedAt
     */
    public function setSubmittedAt(\DateTime $submittedAt): void
    {
        $this->submittedAt = $submittedAt;
    }

    /**
     * @return Product[]
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param Product[] $products
     */
    public function setProducts(array $products): void
    {

        $this->products = $products;
    }

    public function addProduct($product): void
    {
        $this->products->add($product);
    }

    public function removeProduct($product)
    {
        return $this->products->removeElement($product);
    }
}
