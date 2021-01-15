<?php

namespace App\Model\Entity;

use \App\Model\Entity\Entity;

class Post extends Entity
{
	protected string $title = "";
	protected string $chapo = "";
	protected string $content = "";
	protected int $idAuthor = 1;
	protected int $active = 0;
	protected array $categories = [];
	protected $createdAt = "";
	protected $updatedAt = "";

	/**
	 * @return string
	 */
	public function getTitle(): string
	{
		return $this->title;
	}
	/**
	 * @return string
	 */
	public function getChapo(): string
	{
		return $this->chapo;
	}
	/**
	 * @return string
	 */
	public function getContent(): string
	{
		return $this->content;
	}
	/**
	 * @return integer
	 */
	public function getActive(): int
	{
		return $this->active;
	}
	/**
	 * @return array
	 */
	public function getCategories(): array
	{
		return $this->categories;
	}
	/**
	 * @return integer
	 */
	public function getIdAuthor(): int
	{
		return $this->idAuthor;
	}
	/**
	 * @return void
	 */
	public function getCreatedAt()
	{
		return $this->created_at;
	}
	/**
	 * @return void
	 */
	public function getUpdatedAt()
	{
		return $this->updated_at;
	}

	/**
	 * @param string $title
	 * @return self
	 */
	public function setTitle(string $title): self
	{
		$this->title = $title;
		return $this;
	}
	/**
	 * @param string $chapo
	 * @return self
	 */
	public function setChapo(string $chapo): self
	{
		$this->chapo = $chapo;
		return $this;
	}
	/**
	 * @param string $content
	 * @return self
	 */
	public function setContent(string $content): self
	{
		if (!empty($content)) {
			$this->content = $content;
		}
		return $this;
	}
	/**
	 * @param integer $id_author
	 * @return self
	 */
	public function setIdAuthor(int $idAuthor): self
	{
		if ($idAuthor > 0) {
			$this->idAuthor = $idAuthor;
		}
		return $this;
	}
	/**
	 * @param integer $active
	 * @return self
	 */
	public function setActive(int $active): self
	{
		$this->active = $active;
		return $this;
	}
	/**
	 * @param array $categories
	 * @return self
	 */
	public function setCategories(array $categories): self
	{
		$this->categories = $categories;
		return $this;
	}
	/**
	 * @param [type] $created_at
	 * @return self
	 */
	public function setCreatedAt($createdAt): self
	{
		if (!empty($createdAt)) {
			$this->createdAt = $createdAt;
		}
		return $this;
	}

	/**
	 * @param [type] $updated_at
	 * @return self
	 */
	public function setUpdatedAt($updatedAt): self
	{
		if (!empty($updatedAt)) {
			$this->updatedAt = $updatedAt;
		}
		return $this;
	}
}
