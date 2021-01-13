<?php

namespace App\Model\Entity;

use \App\Model\Entity\Entity;

class Comment extends Entity
{
    protected string $content = "";
    protected int $idAuthor = 0;
    protected string $authorName = "";
    protected int $idPost = 0;
    protected bool $active = false;
    protected string $createdAt = "";


    /**
     * Get the value of content
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Set the value of content
     *
     * @return  self
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get the value of idAuthor
     */
    public function getIdAuthor(): int
    {
        return $this->idAuthor;
    }

    /**
     * Set the value of idAuthor
     *
     * @return  self
     */
    public function setIdAuthor(int $idAuthor): self
    {
        $this->idAuthor = $idAuthor;

        return $this;
    }

    /**
     * Get the value of idPost
     */
    public function getIdPost(): int
    {
        return $this->idPost;
    }

    /**
     * Set the value of idPost
     *
     * @return  self
     */
    public function setIdPost(int $idPost): self
    {
        $this->idPost = $idPost;

        return $this;
    }

    /**
     * Get the value of active
     */
    public function getActive(): bool
    {
        return $this->active;
    }

    /**
     * Set the value of active
     *
     * @return  self
     */
    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get the value of createdAt
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * Set the value of createdAt
     *
     * @return  self
     */
    public function setCreatedAt(string $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get the value of author_name
     *
     * @return string
     */
    public function getAuthorName(): string
    {
        return $this->authorName;
    }

    /**
     * Set the value of author_name
     *
     * @param string $author_name
     * @return self
     */
    public function setAuthorName(string $authorName): self
    {
        $this->authorName = $authorName;

        return $this;
    }
}
