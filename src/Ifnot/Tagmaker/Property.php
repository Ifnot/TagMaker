<?php namespace Ifnot\Tagmaker;

/**
 * Class Property
 * @package Ifnot\Tagmaker
 */
class Property {

	protected $name;
	protected $value;
	protected $collection;

	public static $collections = ['style', 'class'];

	/**
	 * @param      $name
	 * @param      $value
	 * @param bool $collection
	 *
	 */
	public function __construct($name, $value, $collection = null)
	{
		// Auto detect if the attribute should be a collection or not (if no $collection defined)
		if(is_null($collection)) {
			if(in_array($name, self::$collections)) $collection = true;
			else $collection = false;
		}

		$this->name = $name;
		$this->collection = $collection;

		if($this->isCollection() AND !is_array($value))
			$this->value = [$value];
		else
			$this->value = $value;
	}

	/**
	 * @param $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * @return mixed
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param $value
	 */
	public function setValue($value)
	{
		$this->value = $value;
	}

	/**
	 * @return array
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * @param Property $property
	 */
	public function merge(Property $property)
	{
		$value = $property->getValue();

		if($this->isCollection()) {
			if(!is_array($value)) $value = [$value];
			$this->value = array_merge($this->value, $value);
		}
		else {
			$this->value = $value;
		}

		return $this;
	}

	/**
	 * @return bool
	 */
	public function isCollection()
	{
		return $this->collection;
	}

	/**
	 * @return string
	 */
	public function render()
	{
		if(is_array($this->value))
			$value = implode(' ', $this->value);
		else
			$value = $this->value;

		$value = str_replace('"', '\"', $value);

		return $this->name . '="' . $value . '"';
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->render();
	}

	/**
	 * @return string
	 */
	public function toString()
	{
		return $this->__toString();
	}
}