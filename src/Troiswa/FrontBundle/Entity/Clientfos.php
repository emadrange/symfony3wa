<?php
/**
 * Created by PhpStorm.
 * User: wap75
 * Date: 28/07/15
 * Time: 15:44
 */

namespace Troiswa\FrontBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="client")
 */
class Clientfos extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var
     *
     * @ORM\OneToMany(targetEntity="Troiswa\FrontBundle\Entity\Comment", mappedBy="client")
     */
    private $comments;

    public function __construct()
    {
        parent::__construct();
        // your own logic
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
     * Add comments
     *
     * @param \Troiswa\FrontBundle\Entity\Comment $comments
     * @return Clientfos
     */
    public function addComment(\Troiswa\FrontBundle\Entity\Comment $comments)
    {
        $this->comments[] = $comments;

        return $this;
    }

    /**
     * Remove comments
     *
     * @param \Troiswa\FrontBundle\Entity\Comment $comments
     */
    public function removeComment(\Troiswa\FrontBundle\Entity\Comment $comments)
    {
        $this->comments->removeElement($comments);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getComments()
    {
        return $this->comments;
    }
}
