<?php

namespace App\Model\Entity;

use \App\Model\Entity\Entity;

class Post extends Entity
{
	protected string $title = "";
	protected string $chapo = "";
	protected string $content = "";
	protected User $author;
	protected int $active = 0;
	/** $categories Category[] */
	protected array $categories = [];
	protected $createdAt = "";
	protected $updatedAt = "";

	public function getTitle(): string
	{
		return $this->title;
	}

	public function getChapo(): string
	{
		return $this->chapo;
	}

	public function getContent(): string
	{
		return $this->content;
	}

	public function getActive(): int
	{
		return $this->active;
	}

	public function getCategories(): array
	{
		return $this->categories;
	}

	public function getAuthor(): object
	{
		return $this->author;
	}

	public function getCreatedAt()
	{
		return $this->created_at;
	}

	public function getUpdatedAt()
	{
		return $this->updated_at;
	}

	public function setTitle(string $title): self
	{
		$this->title = $title;
		return $this;
	}

	public function setChapo(string $chapo): self
	{
		$this->chapo = $chapo;
		return $this;
	}

	public function setContent(string $content): self
	{
		if (!empty($content)) {
			$this->content = $content;
		}
		return $this;
	}

	public function setAuthor(object $author): self
	{
		if ($author) {
			$this->author = $author;
		}
		return $this;
	}


	public function setActive(int $active): self
	{
		$this->active = $active;
		return $this;
	}


	public function setCategories(array $categories): self
	{
		$this->categories = $categories;
		return $this;
	}

	public function setCreatedAt($createdAt): self
	{
		if (!empty($createdAt)) {
			$this->createdAt = $createdAt;
		}
		return $this;
	}

	public function setUpdatedAt($updatedAt): self
	{
		if (!empty($updatedAt)) {
			$this->updatedAt = $updatedAt;
		}
		return $this;
	}
}
