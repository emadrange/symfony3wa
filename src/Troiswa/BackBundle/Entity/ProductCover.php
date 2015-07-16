<?php

namespace Troiswa\BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ProductCover
 *
 * @ORM\Table(name="product_cover")
 * @ORM\Entity(repositoryClass="Troiswa\BackBundle\Entity\ProductCoverRepository")
 */
class ProductCover
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="alt", type="string", length=255)
     */
    private $alt;

    /**
     * @var
     *
     * @Assert\Image(
     *      maxWidth = 400,
     *      maxHeight = 400,
     *      maxWidthMessage = "Largeur de l'image trop grande",
     *      maxHeightMessage = "Hauteur de l'image trop grande",
     *      mimeTypes = {"image/jpeg", "image/gif", "image/png"},
     *      mimeTypesMessage = "Type d'image non valide"
     * )
     */
    private $fichier;


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
     * @return ProductCover
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
     * @return ProductCover
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
     * @param UploadedFile $fichier
     * @return ProductCover
     */
    public function setFichier(UploadedFile $fichier = null)
    {
        $this->fichier = $fichier;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFichier()
    {
        return $this->fichier;
    }

    /**
     * Charge l'image et initialise le nom avec la valeur du alt
     */
    public function upload() {

        if (null == $this->fichier) {
            return;
        }

        $date = new \DateTime('now');

        //$nameImage = $date->format('YmdHis') . '-' . $this->fichier->getClientOriginalName();
        $extension = $this->fichier->guessExtension();
        $nameImage = str_replace(' ', '-', $this->alt) . uniqid();

        // move (param 1 chemin vers dossier, param 2 nom image)

        $this->name = $nameImage . "." . $extension;

        $this->fichier->move(
            $this->getUploadRootDir(),
            $nameImage . "." . $extension
        );


        $imagine = new \Imagine\Gd\Imagine();

        $imagine
            ->open($this->getAbsolutePath())
            ->thumbnail(new \Imagine\Image\Box(350, 160))
            ->save(
                $this->getUploadRootDir().'/' .
                $nameImage.'-thumb-small.'.$extension);



    }

    /**
     * Retourne le chemin pour le upload de l'image
     *
     * @return string
     */
    private function getUploadRootDir() {

        return __DIR__ . "/../../../../web/upload/products";
    }

    /**
     * Retourne le chemin avec l'image
     *
     * @return string
     */
    public function getWebPath() {

        return "/upload/products/" . $this->name;
    }

    /**
     * Retourne le chemin absolu
     * @return string
     */
    public function getAbsolutePath()
    {
        return $this->getUploadRootDir().'/'.$this->name;
    }
}
