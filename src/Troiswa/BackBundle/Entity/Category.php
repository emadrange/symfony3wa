<?php

namespace Troiswa\BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Troiswa\BackBundle\Entity\Product;

/**
 * Category
 *
 * @ORM\Table(name="category")
 * @ORM\Entity(repositoryClass="Troiswa\BackBundle\Repository\CategoryRepository")
 */
class Category
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
     *
     * @ORM\Column(name="titre", type="string", length=100)
     * @Assert\NotBlank(message="titre obligatoire")
     * @Assert\Length(
     *      max="100",
     *      maxMessage="100 caractères maximum"
     * )
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     * @Assert\NotBlank(message="description obligatoire")
     * @Assert\Length(
     *      min="10",
     *      max="200",
     *      minMessage="minimum 10 caractères pour la description",
     *      maxMessage="200 caractères maximum"
     * )
     */
    private $description;


    /**
     * @var integer
     *
     * @ORM\Column(name="position", type="integer")
     * @Assert\NotBlank(message="position obligatoire")
     * @Assert\GreaterThanOrEqual(
     *     value = 0,
     *     message="Valeur impossible"
     * )
     */
    private $position;

    /**
     * @var
     *
     * @ORM\OneToMany(targetEntity="Product", mappedBy="cat")
     */
    private $products;


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
     * Set titre
     *
     * @param string $titre
     * @return Category
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string 
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Category
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
     * Set position
     *
     * @param integer $position
     * @return Category
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @return string
     */
    public function __toString() {
        return $this->titre;
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
     * @return Category
     */
    public function addProduct(\Troiswa\BackBundle\Entity\Product $products)
    {
        $this->products[] = $products;

        // set la catégorie dans le produit lors de l'ajout d'une catégorie
        $products->setCat($this);

        //dump($products);
        //die();

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

        // set la catégorie à null
        $products->setCat(null);
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
     * Validation pour tout le formulaire
     * @author Eric
     * 
     * @return bool
     *
     * @Assert\True(message="Catégorie invalide, la position 0 doit se nommer uniquement Accueil")
     */
    public function isCategoryValid()
    {
        if ($this->position == 0 && $this->titre != "Accueil")
        {
            return false;
        }

        return true;
    }

    /**
     * Validation pour un champ mais ne fonctionne pas pour le moment
     * @return bool
     *
     * @Assert\True(message="Le titre doit commencer par une majuscule")
     */
    /*public function isTitre() {

        dump($this->titre);
        die();

        if (!preg_match('/^[A-Z]/', $this->titre)) {

            return false;
        }

        return true;
    }*/

    /**
     * Validation par Callback, le titre doit commencer par une majuscule
     * @author Eric
     *
     * @Assert\Callback()
     */
    public function validate(ExecutionContextInterface $context)
    {
        if (!preg_match('/^[A-Z]/', $this->titre)) {

            // les doubles accolades sont une simple convention, on peut mettre ce que l'on veut
            $context->buildViolation('Le titre "{{value}}" doit commencer par une majuscule')
                ->atPath("titre")
                ->setParameter('{{value}}', $this->titre)
                ->addViolation();
        }
    }
}
