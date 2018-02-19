<?php
namespace AppBundle\Entity;

/**
 * @Doctrine\ORM\Mapping\Entity(repositoryClass="\AppBundle\Repository\UserRepository")
 * @Doctrine\ORM\Mapping\Table(name="user")
 */
class User extends \FOS\UserBundle\Model\User
{
    const ROLE_CLIENT = 'ROLE_CLIENT';
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const GROUP_ADMIN = 'GROUP_ADMIN';
    const GROUP_CLIENT = 'GROUP_CLIENT';

    /**
     * @Doctrine\ORM\Mapping\Id
     * @Doctrine\ORM\Mapping\Column(type="integer")
     * @Doctrine\ORM\Mapping\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Doctrine\ORM\Mapping\ManyToMany(targetEntity="AppBundle\Entity\Group")
     * @Doctrine\ORM\Mapping\JoinTable(name="fos_user_user_group",
     *      joinColumns={@Doctrine\ORM\Mapping\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@Doctrine\ORM\Mapping\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;

    /**
     * @Doctrine\ORM\Mapping\ManyToOne(targetEntity="CurrencyZone")
     * @Doctrine\ORM\Mapping\JoinColumn(name="currency_zone_id", referencedColumnName="id", nullable=false)
     */
    private $currencyZone;

    /**
     * @Doctrine\ORM\Mapping\OneToMany(targetEntity="Cart", mappedBy="user")
     */
    private $carts;


    /**
     * User constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->carts = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getCurrencyZone()
    {
        return $this->currencyZone;
    }

    /**
     * @param mixed $currencyZone
     */
    public function setCurrencyZone($currencyZone): void
    {
        $this->currencyZone = $currencyZone;
    }

}