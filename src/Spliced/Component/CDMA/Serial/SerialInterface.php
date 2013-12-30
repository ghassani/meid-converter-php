<?php 

namespace Spliced\Component\CDMA\Serial;

/**
 * SerialInterface
 * 
 * Represents a Serial Number
 */
interface SerialInterface{

	public function __construct($serial = null);
	/**
	 * __toString
	 * @return stirng
	 */
	public function __toString();
	
	/**
	 * setInput
	 * @param string $value
	 * @return Serial
	 */
	public function setInput($value);
	
	/**
	 * getInput
	 * @return string
	 */
	public function getInput();
	
	/**
	 * getType
	 * @return string
	 */
	public function getType();
	
	/**
	 * getType
	 * @return string
	 */
	public function getInputType();
	
	/**
	 * setValue
	 * @return string
	 */
	public function setDecimal($decSerial);
	
	/**
	 * setValue
	 * @return string
	 */
	public function getDecimal();
	
	/**
	 * setValue
	 * @return string
	 */
	public function setHexadecimal($hexSerial);
	
	/**
	 * setValue
	 * @return string
	 */
	public function getHexadecimal();
}