<?php namespace Ifnot\Tagmaker;

/**
 * Class Tag
 * @package Ifnot\Tagmaker
 */
class Tag {

	protected $mode;

	protected $name;
	protected $value;
	protected $properties;

	const NORMAL = 0;
	const SELF_CLOSING = 1;

	/**
	 * @param string $name
	 * @param null   $value
	 */
	public function __construct($name = "div", $value = null)
	{
		$this->name = $name;
		$this->value = $value;
		$this->properties = [];
	}

	/**
	 * @param $value
	 */
	public function setValue($value)
	{
		$this->value = $value;
	}

	/**
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->value;
	}

	/**
	 * @param $name
	 */
	public function setName($name)
	{
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param $mode
	 */
	public function setMode($mode)
	{
		$this->mode = $mode;
	}

	/**
	 * @param $mode
	 *
	 * @return mixed
	 */
	public function getMode($mode)
	{
		return $mode;
	}

	/**
	 * @param Property $property
	 */
	public function add(Property $property)
	{
		$propertyName = $property->getName();

		if(isset($this->properties[$propertyName])) {
			$existantProperty = $this->properties[$propertyName];
			$this->properties[$propertyName] = $existantProperty->merge($property);
		}
		else {
			$this->properties[$propertyName] = $property;
		}
	}

	/**
	 * @param null $mode
	 *
	 * @return string
	 */
	public function render($mode = null)
	{
		if(!is_null($mode))
			$this->mode = $mode;

		$html = '<' . $this->name;

		foreach($this->properties as $property) {
			$html .= ' ' . $property->render();
		}

		if($this->mode == self::NORMAL)
			$html .= '>' . $this->value . '</' . $this->name . '>';
		else
			$html .= ' />';

		return $html;
	}

	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->render();
	}
}