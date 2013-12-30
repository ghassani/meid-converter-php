<?php 

namespace Spliced\Component\CDMA\SpcCalculator;

use Spliced\Component\CDMA\Serial\SerialInterface;

class VerizonCalculator implements CalculatorInterface{
	
	const CARRIER = 'Verizon';
	
	public function calculate(SerialInterface $serial){
		return '000000';
	}
	
	public function getCarrier(){
		return self::CARRIER;
	}
}