<?php
/**
 * Created by PhpStorm.
 * User: wap75
 * Date: 22/07/15
 * Time: 15:45
 */

namespace Troiswa\BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Coupon
 *
 * @ORM\Table(name="user_coupon")
 * @ORM\Entity(repositoryClass="Troiswa\BackBundle\Repository\UserCouponRepository")
 */
class UserCoupon
{
    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Troiswa\BackBundle\Entity\User", inversedBy="coupon")
     * @ORM\JoinColumns({
     *			@ORM\JoinColumn(name="id_user", referencedColumnName="id")
     * })
     */
    private $user;


    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="Troiswa\BackBundle\Entity\Coupon")
     * @ORM\JoinColumns({
     *			@ORM\JoinColumn(name="id_coupon", referencedColumnName="id")
     * })
     */
    private $coupon;

    /**
     * @var
     *
     * @ORM\Column(name="used", type="boolean")
     */
    private $used;

    /**
     * Set user
     *
     * @param \Troiswa\BackBundle\Entity\User $user
     * @return UserCoupon
     */
    public function setUser(\Troiswa\BackBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Troiswa\BackBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set coupon
     *
     * @param \Troiswa\BackBundle\Entity\Coupon $coupon
     * @return UserCoupon
     */
    public function setCoupon(\Troiswa\BackBundle\Entity\Coupon $coupon)
    {
        $this->coupon = $coupon;

        return $this;
    }

    /**
     * Get coupon
     *
     * @return \Troiswa\BackBundle\Entity\Coupon 
     */
    public function getCoupon()
    {
        return $this->coupon;
    }

    /**
     * Set used
     *
     * @param boolean $used
     * @return UserCoupon
     */
    public function setUsed($used)
    {
        $this->used = $used;

        return $this;
    }

    /**
     * Get used
     *
     * @return boolean 
     */
    public function getUsed()
    {
        return $this->used;
    }
}
