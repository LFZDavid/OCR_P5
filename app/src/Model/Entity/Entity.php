<?php

namespace App\Model\Entity;

abstract class Entity
{
	protected $id;

	public function __construct($data = [])
	{
		if(!empty($data)){
			$this->hydrate($data);
		}
	}
	public function hydrate($data)
	{
		foreach($data as $attribut => $value){
			$method = 'set'.ucfirst($attribut);

			if(is_callable([$this, $method])){
				$this->$method($value);
			}
		}
	}
//GETTERS
	public function id(){
		return $this->id;
	}
//SETTERS
	public function setId($id)
	{
		$id = (int) $id;
		if($id > 0){
			$this->id = $id;
		}
	}
}