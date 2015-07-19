<?php

namespace Troiswa\BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Troiswa\BackBundle\Validator\Antigrosmots;

/**
 * Marque
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Troiswa\BackBundle\Entity\MarqueRepository")
 */
class Marque
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @Assert\NotBlank(message="Il faut une marque")
     * @Assert\Length(
     *      min = 2,
     *      max = 100,
     *      minMessage = "Le titre doit faire {{ limit }} caractères minimum",
     *      maxMessage = "Le titre doit faire {{ limit }} caractères maximum"
     * )
     * @ORM\Column(name="title", type="string", length=100)
     */
    private $title;

    /**
     * @var string
     * @Assert\NotBlank(message="Il faut une description")
     * @Assert\Length(
     *      min = 10,
     *      max = 500,
     *      minMessage = "La description doit faire {{ limit }} caractères minimum",
     *      maxMessage = "La description doit faire {{ limit }} caractères maximum"
     * )
     * @ORM\Column(name="description", type="text")
     * @Antigrosmots()
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=128)
     * @Gedmo\Slug(fields={"title"}, updatable=false, separator="_")
     */
    private $slug;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated", type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    private $updated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="content_changed", type="datetime", nullable=true)
     * @Gedmo\Timestampable(on="change", field={"title"})
     */
    private $contentChanged;

    /**
     * @var
     *
     * @ORM\OneToMany(targetEntity="Product", mappedBy="marque")
     */
    private $products;

    /**
     * @var BrandLogo
     *
     * @ORM\OneToOne(targetEntity="Troiswa\BackBundle\Entity\BrandLogo", cascade={"persist"})
     * @ORM\JoinColumn(name="id_logo", referencedColumnName="id")
     * @Assert\Valid()
     */
    private $logo;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Marque
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Marque
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
     * Set slug
     *
     * @param string $slug
     * @return Marque
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Marque
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return Marque
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set contentChanged
     *
     * @param \DateTime $contentChanged
     * @return Marque
     */
    public function setContentChanged($contentChanged)
    {
        $this->contentChanged = $contentChanged;

        return $this;
    }

    /**
     * Get contentChanged
     *
     * @return \DateTime 
     */
    public function getContentChanged()
    {
        return $this->contentChanged;
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add products
     *
     * @param \Troiswa\BackBundle\Entity\Product $products
     * @return Marque
     */
    public function addProduct(\Troiswa\BackBundle\Entity\Product $products)
    {
        $this->products[] = $products;

        $products->setMarque($this);

        return $this;
    }

    /**
     * Remove products
     *
     * @param \Troiswa\BackBundle\Entity\Product $products
     */
    public function removeProduct(\Troiswa\BackBundle\Entity\Product $products)
    {
        $this->products->removeElement($products);

        $products->setMarque(null);
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @return string
     */
    public function __toString() {

        return $this->title;
    }

    /**
     * Label spécial pour afficher le titre avec sa date de création
     * @author Eric
     * @return string
     */
    public function getTitleAndDate() {

        return $this->title . ' (' . $this->created->format('d - m - Y') . ')';
    }

    /**
     * Validation de titre interdit par callback
     * @author Eric
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context) {

        if (strtolower($this->title) == "3wa" || strtolower($this->title == "troiswa")) {
            $context->buildViolation("La marque 3wa est interdite")
                ->atPath('title')
                ->addViolation();
        }
    }

    /**
     * Set logo
     *
     * @param \Troiswa\BackBundle\Entity\BrandLogo $logo
     * @return Marque
     */
    public function setLogo(\Troiswa\BackBundle\Entity\BrandLogo $logo = null)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo
     *
     * @return \Troiswa\BackBundle\Entity\BrandLogo 
     */
    public function getLogo()
    {
        return $this->logo;
    }
}
