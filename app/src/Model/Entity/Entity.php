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
			$method = 'set' . ucfirst($attribut);

			if (is_callable([$this, $method])) {
				$this->$method($value);
			}
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
}
