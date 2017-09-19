<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Item
 *
 * @ORM\Table(name="item")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ItemRepository")
 */
class Item
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="code", type="integer", unique=true)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=500)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=100)
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity="Section", inversedBy="items")
     * @ORM\JoinColumn(name="section_id", referencedColumnName="id")
     */
    private $section;

    /**
     * @var int
     *
     * @ORM\Column(name="unitaryPrice", type="integer", nullable=true)
     */
    private $unitaryPrice;

    /**
     * @var int
     *
     * @ORM\Column(name="comboPrice", type="integer", nullable=true)
     */
    private $comboPrice;

    /**
     * @var bool
     *
     * @ORM\Column(name="isFeatured", type="boolean")
     */
    private $isFeatured;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var bool
     *
     * @ORM\Column(name="isActive", type="boolean")
     */
    private $isActive;

    /**
     * @var bool
     *
     * @ORM\Column(name="isDuo", type="boolean")
     */
    private $isDuo;

    /**
     * @var bool
     *
     * @ORM\Column(name="showSelections", type="boolean")
     */
    private $showSelections;


    public function __construct()
    {
        $this->isFeatured = false;
        $this->createdAt= new \DateTime();
        $this->isActive = true;
        $this->isDuo = false;
        $this->showSelections = true;
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set code
     *
     * @param integer $code
     *
     * @return Item
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Item
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Item
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Item
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set section
     *
     * @param \AppBundle\Entity\Section $section
     *
     * @return Project
     */
    public function setSection(\AppBundle\Entity\Section $section = null)
    {
        $this->section = $section;

        return $this;
    }

    /**
     * Get section
     *
     * @return \AppBundle\Entity\Section
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * Set unitaryPrice
     *
     * @param integer $unitaryPrice
     *
     * @return Item
     */
    public function setUnitaryPrice($unitaryPrice)
    {
        $this->unitaryPrice = $unitaryPrice;

        return $this;
    }

    /**
     * Get unitaryPrice
     *
     * @return int
     */
    public function getUnitaryPrice()
    {
        return $this->unitaryPrice;
    }

    /**
     * Set comboPrice
     *
     * @param integer $comboPrice
     *
     * @return Item
     */
    public function setComboPrice($comboPrice)
    {
        $this->comboPrice = $comboPrice;

        return $this;
    }

    /**
     * Get comboPrice
     *
     * @return int
     */
    public function getComboPrice()
    {
        return $this->comboPrice;
    }

    /**
     * Set isFeatured
     *
     * @param boolean $isFeatured
     *
     * @return Item
     */
    public function setIsFeatured($isFeatured)
    {
        $this->isFeatured = $isFeatured;

        return $this;
    }

    /**
     * Get isFeatured
     *
     * @return bool
     */
    public function getIsFeatured()
    {
        return $this->isFeatured;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Item
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Item
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return bool
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set isDuo
     *
     * @param boolean $isDuo
     *
     * @return Item
     */
    public function setIsDuo($isDuo)
    {
        $this->isDuo = $isDuo;

        return $this;
    }

    /**
     * Get isDuo
     *
     * @return bool
     */
    public function getIsDuo()
    {
        return $this->isDuo;
    }

    /**
     * Set showSelections
     *
     * @param boolean $showSelections
     *
     * @return Item
     */
    public function setshowSelections($showSelections)
    {
        $this->showSelections = $showSelections;

        return $this;
    }

    /**
     * Get showSelections
     *
     * @return bool
     */
    public function getshowSelections()
    {
        return $this->showSelections;
    }
}

