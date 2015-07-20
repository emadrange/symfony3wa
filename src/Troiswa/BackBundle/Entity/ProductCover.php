<?php

namespace Troiswa\BackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ProductCover
 *
 * @ORM\Table(name="product_cover")
 * @ORM\Entity(repositoryClass="Troiswa\BackBundle\Repository\ProductCoverRepository")
 * @ORM\HasLifecycleCallbacks
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
     * @Assert\NotBlank(message="Il faut une photo ou une image")
     * @Assert\Image(
     *      maxWidth = 400,
     *      maxHeight = 400,
     *      maxWidthMessage = "Largeur de l'image trop grande",
     *      maxHeightMessage = "Hauteur de l'image trop grande",
     *      mimeTypes = {"image/jpeg", "image/gif", "image/png"},
     *      mimeTypesMessage = "Type d'image non valide",
     *      sizeNotDetectedMessage = "Impossible de charger l'image",
     *      maxSize = "400k",
     *      maxSizeMessage = "L'image est trop importante"
     * )
     */
    private $fichier;

    /**
     * @var array
     */
    private $thumbnails = [
        "thumb-small"  => [100, 50],
        "thumb-medium" => [150, 100],
        "thumb-large"  => [300, 160]
    ];

    private $oldFichier;

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

        // Test si j'ai déjà une image
        if ($this->name)
        {
            // J'ajoute dans oldFichier l'ancienne image
            $this->oldFichier = $this->name;
            // ecrasement de name pour que Doctrine voit le changement
            $this->name = null;
        }

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
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload() {
        $this->name = str_replace(' ', '-', $this->alt) . uniqid() . "." . $this->fichier->guessExtension();
    }

    /**
     * Charge l'image et initialise le nom avec la valeur du alt
     *
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload() {

        if (null == $this->fichier) {
            return;
        }

        if ($this->oldFichier) {
            // suppression de l'ancienne image
            unlink($this->getUploadRootDir() . '/' . $this->oldFichier);

            // suppression des anciens thumbnails
            foreach ($this->thumbnails as $key => $thumbnail) {
                unlink($this->getUploadRootDir() . '/' . $key . '-' . $this->oldFichier);
            }
        }

        //$date = new \DateTime('now');

        //$nameImage = $date->format('YmdHis') . '-' . $this->fichier->getClientOriginalName();
        //$extension = $this->fichier->guessExtension();
        //$nameImage = str_replace(' ', '-', $this->alt) . uniqid();

        // move (param 1 chemin vers dossier, param 2 nom image)

        //$this->name = $nameImage . "." . $extension;

        $this->fichier->move(
            $this->getUploadRootDir(),
            $this->name
            //$nameImage . "." . $extension
        );


        //$imagine = new \Imagine\Gd\Imagine();

        /*$imagine
            ->open($this->getAbsolutePath())
            ->thumbnail(new \Imagine\Image\Box(350, 160))
            ->save(
                $this->getUploadRootDir().'/' .
                $nameImage . '-thumb-small.' . $extension);*/

        $imagine = new \Imagine\Gd\Imagine();
        $imagineOpen = $imagine->open($this->getAbsolutePath());

        foreach ($this->thumbnails as $key => $thumbnail) {
            $imagineOpen->thumbnail(new \Imagine\Image\Box($thumbnail[0], $thumbnail[1]))
                ->save($this->getUploadRootDir() . '/' . $key . '-' . $this->name);
        }
    }

    /**
     * Récupération de l'objet à deleter avec le PreRemove()
     * @ORM\PreRemove()
     */
    public function preRemoveImage() {
        // cette method peut être vide
        // dump($this);
        // die();
    }

    /**
     * Supprime les images du produit
     * @ORM\PostRemove()
     */
    public function removeImage() {

        // Suppression de l'image principale
        $fichier = $this->getAbsolutePath();

        if (file_exists($fichier)) {
            unlink($fichier);
        }

        // Suppression des thumbnails
        foreach($this->thumbnails as $key => $thumbnail) {
            $thumb = $this->getUploadRootDir() . '/' . $key . '-' . $this->name;
            if (file_exists($thumb)) {
                unlink($thumb);
            }
        }
    }

    /**
     * Retourne le chemin pour le upload de l'image
     *
     * @return string
     */
    private function getUploadRootDir() {

        return __DIR__ . "/../../../../web" . $this->getUploadDir();
    }

    /**
     * Retourne le chemin avec l'image
     *
     * @return string
     */
    public function getWebPath($thumb = null) {

        /*$thumbnail = "";

        if (null != $thumb) {
            $thumbnail = $thumb . "-";
        }*/

        if (array_key_exists($thumb, $this->thumbnails)) {
            return $this->getUploadDir() . "/" . $thumb . "-". $this->name;
        } else {
            return $this->getUploadDir() . "/" . $this->name;
        }
    }

    /**
     * Retourne le chemin absolu
     * @return string
     */
    public function getAbsolutePath()
    {
        return $this->getUploadRootDir().'/'.$this->name;
    }

    private function getUploadDir() {

        return "/upload/products";
    }
}
