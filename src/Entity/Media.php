<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MediaRepository")
 */
class Media
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $img1920;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $img250;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Article", inversedBy="media", cascade={"persist", "remove"})
     */
    private $article;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImg1920(): ?string
    {
        return $this->img1920;
    }

    public function setImg1920(string $img1920): self
    {
        $this->img1920 = $img1920;

        return $this;
    }

    public function getImg250(): ?string
    {
        return $this->img250;
    }

    public function setImg250(string $img250): self
    {
        $this->img250 = $img250;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): self
    {
        $this->article = $article;

        return $this;
    }
}
