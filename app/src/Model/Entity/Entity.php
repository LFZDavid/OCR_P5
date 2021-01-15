<?php

namespace App\Model\Entity;

abstract class Entity
{
	protected int $id;

	public function __construct(array $data = [])
	{
		if (!empty($data)) {
			$this->hydrate($data);
		}
	}
	public function hydrate(array $data)
	{
		foreach ($data as $attribut => $value) {
			$this->__set($attribut, $value);
		}
	}

	public function __set($name, $value)
	{
		if (isset($this->$name)) {
			$this->$name = $value;
		} else if (false !== strpos($name, '_')) {
			$this->__set($this->snakeCaseToCamelCase($name), $value);
		}
	}

	/**
	 * @return integer
	 */
	public function getId(): int
	{
		return $this->id ?? false;
	}

	/**
	 * @param integer $id
	 * @return self
	 */
	public function setId(int $id): self
	{
		if ($id > 0) {
			$this->id = $id;
			return $this;
		}
	}

	/**
	 *
	 * @param string $str
	 * @return string
	 */
	protected function snakeCaseToCamelCase($str)
	{
		$upperCamelCase = str_replace('_', '', ucwords($str, '_'));

		return strtolower(substr($upperCamelCase, 0, 1)) . substr($upperCamelCase, 1);
	}
}
