<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */
class Article
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", length=100)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $body;
    /**
     * @ORM\Column(type="text")
     */
    private $username;

    public function getId()
    {
        return $this->id;
    }

    public function getTitle(){
        return $this->title;
    }

    public function setTitle($title){
        $this->title = $title;
    }
    public function getBody(){
        return $this->body;
    }

    public function setBody($body){
        $this->body = $body;
    }
    public function getUsername()
    {
        return $this->username;
    }
    public function setUsername($username)
    {
        return $this->username = $username;
    }
}
