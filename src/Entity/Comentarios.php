<?php

namespace App\Entity;

use App\Repository\ComentariosRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use IntlDateFormatter;

/**
 * @ORM\Entity(repositoryClass=ComentariosRepository::class)
 */
class Comentarios
{
    public function __construct()
    {
        $this->likes= '';
        $this->data_publicacao= $this->dateGenerate();
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
    private $comentario;

    /**
     * @ORM\Column(type="string")
     */
    private $data_publicacao;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="comentarios")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Posts", inversedBy="comentarios")
     */
    private $posts;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComentario(): ?string
    {
        return $this->comentario;
    }

    public function setComentario(string $comentario): self
    {
        $this->comentario = $comentario;

        return $this;
    }

    public function getDataPublicacao()
    {
        return $this->data_publicacao;
    }

    public function setDataPublicacao(\DateTimeInterface $data_publicacao): self
    {
        $this->data_publicacao = $data_publicacao;

        return $this;
    }

    public function getPosts(): ?string
    {
        return $this->posts;
    }

    public function setPosts($posts): self
    {
        $this->posts = $posts;

        return $this;
    }

    public function getUser()
    {
        $this->user;
        return $this;
    }

    public function setUser($user): self
    {
        $this->user = $user;

        return $this;
    }
}
