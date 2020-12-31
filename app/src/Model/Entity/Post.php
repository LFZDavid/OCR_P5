<?php

namespace App\Model\Entity;

use \App\Model\Entity\Entity;

class Post extends Entity
{
    protected $title;
    protected $chapo;
	protected $content;
	protected $active;
	protected $created_at;
    protected $updated_at;
    
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
        $this->title = $title;
        return $this;
	}
	public function setChapo($chapo)
	{
        $this->chapo = $chapo;
        return $this;
	}
	public function setContent($content)
	{
		if(!empty($content)){
			$this->content = $content;
        }
        return $this;
	}
	public function setActive($active)
	{
		if(!empty($active)){
			$this->active = $active;
        }
        return $this;
	}
	public function setCreated_at($created_at)
	{
		if(!empty($created_at)){
			// if($created_at <= time()){
				$this->created_at = $created_at;
			// }
        }
        return $this;
	}
	public function setUpdated_at($updated_at)
	{
		if(!empty($updated_at)){
			$this->updated_at = $updated_at;
        }
        return $this;
	}

}