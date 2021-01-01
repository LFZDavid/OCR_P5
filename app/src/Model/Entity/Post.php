<?php

namespace App\Model\Entity;

use \App\Model\Entity\Entity;

class Post extends Entity
{
	protected string $title = "";
	protected string $chapo = "";
	protected string $content = "";
	protected int $id_author = 1;
	protected int $active = 0;
	protected $created_at = "";
	protected $updated_at = "";

	//GETTERS
	public function title()
	{
		return $this->title;
	}
	public function chapo()
	{
		return $this->chapo;
	}
	public function content()
	{
		return $this->content;
	}
	public function active()
	{
		return $this->active;
	}
	public function id_author()
	{
		return $this->id_author;
	}
	public function created_at()
	{
		return $this->created_at;
	}
	public function updated_at()
	{
		return $this->updated_at;
	}

	//SETTERS
	public function setTitle($title)
	{
		$this->title = (string) $title;
		return $this;
	}
	public function setChapo($chapo)
	{
		$this->chapo = (string) $chapo;
		return $this;
	}
	public function setContent($content)
	{
		if (!empty($content)) {
			$this->content = (string) $content;
		}
		return $this;
	}
	public function setId_author($id_author)
	{
		if (!empty($id_author)) {
			$this->content = (int) $id_author;
		}
		return $this;
	}
	public function setActive($active)
	{
		$this->active = (int) $active;
		return $this;
	}
	public function setCreated_at($created_at)
	{
		if (!empty($created_at)) {
			$this->created_at = $created_at;
		}
		return $this;
	}
	public function setUpdated_at($updated_at)
	{
		if (!empty($updated_at)) {
			$this->updated_at = $updated_at;
		}
		return $this;
	}
}
