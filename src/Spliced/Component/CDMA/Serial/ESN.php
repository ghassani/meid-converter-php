<?php 

namespace Spliced\Component\CDMA\Serial;

/**
 * ESN
 * 
 * Represents a ESN or pESN Serial Number
 */
class ESN extends AbstractSerial implements SerialInterface{

	protected $rawInput = null;
	protected $inputType = null;
	protected $hexadecimal = null;
	protected $decimal = null;
	
	const VALIDATE_DEC  = '/[0-9]{11}/';
	const VALIDATE_HEX  = '/[a-fA-F0-9]{8}/';
		
	/**
	 * Constructor
	 * 
	 * @param string $serial
	 * @throws InvalidArgumentException
	 * @return SerialInterface 
	 */
	public function __construct($serial = null){
		if(!is_null($serial)){
			return $this->setInput($serial);
		}
	}
	
	/**
	 * setInput
	 * @param string $value
	 * @return SerialInterface
	 */
	public function setInput($serial){
		$this->rawValue = $serial;
		if(preg_match(self::VALIDATE_DEC,$serial)){
			$this->inputType = self::INPUT_DEC;
			return $this->setDecimal($serial);
		} else if(preg_match(self::VALIDATE_HEX,$serial)){
			$this->inputType = self::INPUT_HEX;
			return $this->setHexadecimal($serial);
		}
		throw new \InvalidArgumentException('ESN is not valid');
	}
	
	/**
	 * getInput
	 * @return string
	 */
	public function getInput(){
		return $this->rawValue;
	}
	
	/**
	 * getType
	 * @return string
	 */
	public function getType(){
		return 'MEID';
	}
	
	/**
	 * getInputType
	 * @return string
	 */
	public function getInputType(){
		return $this->inputType;
	}
	
	/**
	 * setDecimal
	 *  @param string - DEC MEID
	 * @return SerialInterface
	 */
	public function setDecimal($decSerial){
		$this->decimal = $decSerial;
		return $this;
	}
	
	/**
	 * getDecimal
	 * @return string
	 */
	public function getDecimal(){
		return $this->decimal;
	}
	/**
	 * setHexadecimal
	 * @param string - HEX MEID
	 * @return SerialInterface
	 */
	public function setHexadecimal($hexSerial){
		$this->hexadecimal = $hexSerial;
		return $this;
	}
	
	/**
	 * getHexadecimal
	 * @return string
	 */
	public function getHexadecimal(){
		return $this->hexadecimal;
	}

}