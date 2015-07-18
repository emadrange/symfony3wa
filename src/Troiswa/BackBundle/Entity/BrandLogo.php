<?php

namespace Troiswa\BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * BrandLogo
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Troiswa\BackBundle\Entity\BrandLogoRepository")
 * @ORM\HasLifecycleCallbacks
 */
class BrandLogo
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
     * @ORM\Column(name="name", type="string", length=128)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="alt", type="string", length=128)
     */
    private $alt;

    /**
     * @var string
     *
     * @ORM\Column(name="legende", type="string", length=100)
     * @Assert\NotBlank(message="légende obligatoire")
     * @Assert\Length(
     *      max="100",
     *      maxMessage="100 caractères maximum"
     * )
     */
    private $legende;

    /**
     * @var
     * @Assert\NotBlank(message="Il faut un logo")
     * @Assert\Image(
     *      maxWidth = 200,
     *      maxHeight = 200,
     *      maxWidthMessage = "Largeur de l'image trop grande",
     *      maxHeightMessage = "Hauteur de l'image trop grande",
     *      mimeTypes = {"image/jpeg", "image/gif", "image/png"},
     *      mimeTypesMessage = "Type d'image non valide",
     *      sizeNotDetectedMessage = "Impossible de charger l'image",
     *      maxSize = "100k",
     *      maxSizeMessage = "L'image est trop importante"
     * )
     */
    private $logofile;
    
    /**
     *
     * @var string
     */
    private $oldLogo;


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
     * Set name
     *
     * @param string $name
     * @return BrandLogo
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
     * Set alt
     *
     * @param string $alt
     * @return BrandLogo
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * Get alt
     *
     * @return string 
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * Set legende
     *
     * @param string $legende
     * @return BrandLogo
     */
    public function setLegende($legende)
    {
        $this->legende = $legende;

        return $this;
    }

    /**
     * Get legende
     *
     * @return string 
     */
    public function getLegende()
    {
        return $this->legende;
    }

    /**
     * Set logofile
     * @author Eric
     *
     * @return BrandLogo
     */
    public function setLogofile($logofile)
    {
        $this->logofile = $logofile;
                
        // Préparation au nouveau nom du logo
        if ($this->name) {
            $this->oldLogo = $this->name;
            $this->name = null;
        }
        
        return $this;
    }

    /**
     * Get logofile
     * @author Eric
     */
    public function getLogofile()
    {
        return $this->logofile;
    }
    
    /**
     * Formate le nom avant le persist
     * @author Eric
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload() {
       
        // formatage du nom du logo
        $this->name = str_replace(' ', '-', $this->alt)
               . "-" . uniqid() . "."
               . $this->logofile->guessExtension(); 
    }

    /**
     * Upload le fichier sélectionné dans le formulaire après le persist
     * @author Eric
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload() {

        if (null == $this->logofile) {
            return;
        }

        if ($this->oldLogo) {
            // supprime l'ancien logo
            unlink($this->getUploadRootDir() . '/' . $this->oldLogo);
        }

        // charge le logo dans le répertoire
        $this->logofile->move(
            $this->getUploadRootDir(),
            $this->name
        );
    }

    /**
     * Retourne le chemin des logos chargés
     * @author Eric
     * @return string
     */
    public function getUploadRootDir() {

        return __DIR__ . '/../../../../web' . $this->getUploadDir();
    }

    /**
     * Retourne le chemin depuis la racine web
     * @author Eric
     * @return string
     */
    public function getWebPath() {

        return $this->getUploadDir() . "/" . $this->name;
    }

    /**
     * Retourne le chemin absolu
     * @author Eric
     * @return string
     */
    public function getAbsolutePath()
    {
        return $this->getUploadRootDir().'/'.$this->name;
    }

    /**
     * Retourne le chemin des logos
     * @author Eric
     * @return string
     */
    private function getUploadDir() {

        return '/upload/logos';
    }


}
