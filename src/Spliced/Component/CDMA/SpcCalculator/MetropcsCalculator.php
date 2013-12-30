<?php 

namespace Spliced\Component\CDMA\SpcCalculator;

use Spliced\Component\CDMA\Serial\SerialInterface;
use Spliced\Component\CDMA\Serial\MEID;

class MetropcsCalculator implements CalculatorInterface{
	
	const CARRIER = 'MetroPCS';
	
	public function calculate(SerialInterface $serial){
		$input = $serial instanceof MEID ? $serial->getPESN()->getDecimal() : $serial->getDecimal();

		$esnDec = str_split($input);
		$subSet = substr($input,8);
		$spc =  (pow(2, (5 + $esnDec[0] + $esnDec[1] + $esnDec[2]) ) -1) *(intval($subSet,10) + 199) *(23+$esnDec[3]+$esnDec[4]+$esnDec[5]+$esnDec[6]+$esnDec[7]+$esnDec[8]+$esnDec[9]+$esnDec[10]);
		return substr($spc,(strlen($spc)-6));
	}
	
	public function getCarrier(){
		return self::CARRIER;
	}

}