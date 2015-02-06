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
	const START_TAG = 2;
	const END_TAG = 3;

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
	 * @param null $value
	 * @param null $mode
	 *
	 * @return string
	 */
	public function render($value = null, $mode = null)
	{
		if(!is_null($value))
			$this->value = $value;

		if(!is_null($mode))
			$this->mode = $mode;

		switch($mode) {
			case self::NORMAL; return $this->renderStartTag() . $this->value . $this->renderEndTag(); break;
			case self::SELF_CLOSING; return $this->renderSelfClosing(true); break;
			case self::START_TAG; return $this->renderStartTag(); break;
			case self::END_TAG; return $this->renderEndTag(); break;
			default: return ""; break;
		}
	}

	/**
	 * @return string
	 */
	protected function renderStartTag()
	{
		$properties = $this->properties;

		$html = '<' . $this->name;
		foreach($properties as $property) {
			$html .= ' ' . $property->render();
		}
		$html .= '>';

		return $html;
	}

	/**
	 * @return string
	 */
	protected function renderEndTag()
	{
		return '</' . $this->name . '>';
	}

	/**
	 * @param bool $bindValueToAttribute
	 *
	 * @return string
	 */
	protected function renderSelfClosing($bindValueToAttribute = false)
	{
		$properties = $this->properties;

		if($bindValueToAttribute)
			$properties['value'] = new Property('value', $this->value);

		$html = '<' . $this->name;
		foreach($properties as $property) {
			$html .= ' ' . $property->render();
		}

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

	/**
	 * @return string
	 */
	public function toString()
	{
		return $this->__toString();
	}
}