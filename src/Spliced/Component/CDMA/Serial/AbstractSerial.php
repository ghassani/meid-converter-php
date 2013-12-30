<?php 

namespace Spliced\Component\CDMA\Serial;

abstract class AbstractSerial {
	const INPUT_HEX 	= 'HEX';
	const INPUT_DEC 	= 'DEC';
	
	protected $spcs = array();
	
	public function __toString(){
		return $this->decimal;
	}
	
	public function setSpcs(array $spcs){
		$this->spcs = $spcs;
		return $this;
	}
	
	public function getSpcs(){
		return $this->spcs;
	}
}