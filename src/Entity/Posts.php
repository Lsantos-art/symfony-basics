<?php

namespace App\Entity;

use App\Repository\PostsRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use IntlDateFormatter;

/**
 * @ORM\Entity(repositoryClass=PostsRepository::class)
 */
class Posts
{

    const SUCCESS_POST = 'Postagem inserida com sucesso!';

    public function __construct()
    {
        $this->likes= '';
        $this->data_post= $this->dateGenerate();
    }

    public function dateGenerate()
    {
        $date = new DateTime();
        $formatter = new IntlDateFormatter(
        'en',
            IntlDateFormatter::FULL,
            IntlDateFormatter::NONE,
            'America/Sao_Paulo',          
            IntlDateFormatter::GREGORIAN
        );
        return $formatter->format($date);
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $titulo;

    /**
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $likes;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $foto;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $data_post;

    /**
     * @ORM\Column(type="string", length=80000)
     */
    private $content;


     /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comentarios", mappedBy="posts")
     */
    private $comentarios;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User",inversedBy="posts")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getLikes(): ?string
    {
        return $this->likes;
    }

    public function setLikes(?string $likes): self
    {
        $this->likes = $likes;

        return $this;
    }

    public function getFoto(): ?string
    {
        return $this->foto;
    }

    public function setFoto(string $foto): self
    {
        $this->foto = $foto;

        return $this;
    }

    public function getDataPost()
    {
        return $this->data_post;
    }

    public function setDataPost(\DateTimeInterface $data_post): self
    {
        $this->data_post = $data_post;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getUser(): ?string
    {
        return $this->user;
    }

    public function setUser($user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getComentarios(): ?string
    {
        return $this->comentarios;
    }

    public function setComentarios(string $comentarios): ?string
    {
        return $this->comentarios = $comentarios;
    }
}
