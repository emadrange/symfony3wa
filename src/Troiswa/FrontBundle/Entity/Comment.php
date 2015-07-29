<?php

namespace Troiswa\FrontBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Comment
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Troiswa\FrontBundle\Repository\CommentRepository")
 */
class Comment
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
     * @ORM\Column(name="content", type="text")
     * @Assert\NotBlank(message="Vous devez saisir un commentaire")
     */
    private $content;

    /**
     * @var integer
     *
     * @ORM\Column(name="rate", type="integer")
     * @Assert\NotBlank(message="Vous devez saisir une note")
     * @Assert\Range(
     *      min = 0,
     *      max = 5,
     *      minMessage = "La note minimum est {{ limit }}",
     *      maxMessage = "La note maximum est {{ limit }}",
     *      invalidMessage = "Vous devez saisir un chiffre de 0 à 5"
     * )
     */
    private $rate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var
     *
     * @ORM\ManyToOne(targetEntity="Troiswa\BackBundle\Entity\Product", inversedBy="comments")
     * @ORM\JoinColumn(name="id_product", referencedColumnName="id")
     */
    private $product;

    /**
     * @var
     *
     * @ORM\ManyToOne(targetEntity="Troiswa\FrontBundle\Entity\Clientfos", inversedBy="comments")
     * @ORM\JoinColumn(name="id_client", referencedColumnName="id")
     */
    private $client;

    /**
     * @var
     *
     * @ORM\ManyToOne(targetEntity="Comment")
     * @ORM\JoinColumn(name="id_parent", referencedColumnName="id")
     */
    private $parent;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->created = new \DateTime("now");
    }

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
     * Set content
     *
     * @param string $content
     * @return Comment
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set rate
     *
     * @param integer $rate
     * @return Comment
     */
    public function setRate($rate)
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * Get rate
     *
     * @return integer 
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Comment
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
     * Set product
     *
     * @param \Troiswa\BackBundle\Entity\Product $product
     * @return Comment
     */
    public function setProduct(\Troiswa\BackBundle\Entity\Product $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \Troiswa\BackBundle\Entity\Product 
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set client
     *
     * @param \Troiswa\FrontBundle\Entity\Clientfos $client
     * @return Comment
     */
    public function setClient(\Troiswa\FrontBundle\Entity\Clientfos $client = null)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \Troiswa\FrontBundle\Entity\Clientfos 
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set parent
     *
     * @param \Troiswa\FrontBundle\Entity\Comment $parent
     * @return Comment
     */
    public function setParent(\Troiswa\FrontBundle\Entity\Comment $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Troiswa\FrontBundle\Entity\Comment 
     */
    public function getParent()
    {
        return $this->parent;
    }
}
