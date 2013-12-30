<?php 

namespace Spliced\Component\CDMA\SpcCalculator;

use Spliced\Component\CDMA\Serial\SerialInterface;

class CricketCalculator implements CalculatorInterface{
	
	const CARRIER = 'Cricket';
	
	public function calculate(SerialInterface $serial){
		return '000000';
	}
	
	public function getCarrier(){
		return self::CARRIER;
	}

}