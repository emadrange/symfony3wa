<?php

namespace Troiswa\BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Troiswa\BackBundle\Validator\Antigrosmots;

/**
 * Tag
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Troiswa\BackBundle\Repository\TagRepository")
 */
class Tag
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
     * @ORM\Column(name="word", type="string", length=50)
     * @Assert\NotBlank(message="Vous devez saisir un mot")
     * @Assert\Length(
     *      max=50,
     *      maxMessage="Le mot est trop long, {{ limit }} caractères maximum"
     * )
     * @Assert\Regex(
     *      pattern = "/^\w+$/",
     *      message = "Vous devez saisir un seul mot"
     * )
     * @Antigrosmots
     */
    private $word;


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
     * Set word
     *
     * @param string $word
     * @return Tag
     */
    public function setWord($word)
    {
        $this->word = $word;

        return $this;
    }

    /**
     * Get word
     *
     * @return string 
     */
    public function getWord()
    {
        return $this->word;
    }
}
