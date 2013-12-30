<?php 

namespace Spliced\Component\CDMA\SpcCalculator;

use Spliced\Component\CDMA\Serial\SerialInterface;

interface CalculatorInterface{
	
	public function calculate(SerialInterface $serial);
	
	public function getCarrier();
	
}