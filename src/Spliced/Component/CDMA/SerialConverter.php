<?php 
namespace Spliced\Component\CDMA;

/**
 * @package MeidConverter
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 * @copyright 2011 Spliced Media L.L.C
 */
class SerialConverter{
    
    /* @param array $spcCalculators - An array of CalculatorInterface */    
    protected $spcCalculators = array();
    
    /**
     * @function __construct
     * Returns a new Instance of this class
     * 
     * @param $input string - Optional input value to start with, or you can set it later
     * @return MeidConverter
     */
    public function __construct(array $spcCalculators = array()){
        foreach($spcCalculators as $calculator){
        	if(!$calculator instanceof SpcCalculator\CalculatorInterface){
        		throw new \InvalidArgumentException('Calculator must be an instance of CalculatorInterface');
        	}
        	$this->spcCalculators[$calculator->getCarrier()] = $calculator;
        }
        return $this;
    }
    
     
    /**
     * Converts the specified input
     * 
     */
    public function convert($serialNumber){  
    	
    	try{
    		$serial = new Serial\MEID($serialNumber);
    	} catch(\Exception $e){
    		$serial = new Serial\ESN($serialNumber);
    	}

    	if($serial instanceof Serial\MEID){
    		if($serial->getInputType() == $serial::INPUT_DEC){
    			$serial->setHexadecimal($this->convertMeidDecToHex($serial));
    		} else {
    			$serial->setDecimal($this->convertMeidHexToDec($serial));
    		}
    		$serial->setPESN($this->calculatePESN($serial));
    	} else {
    		if($serial->getInputType() == $serial::INPUT_DEC){
    			$serial->setHexadecimal($this->convertEsnDecToHex($serial));
    		} else {
    			$serial->setDecimal($this->convertEsnHexToDec($serial));
    		}
    	}
    	
    	$spcs = array();

    	foreach($this->spcCalculators as $carrier => $calculator){
    		$spcs[$carrier] = $calculator->calculate($serial);
    	} 
    	$serial->setSpcs($spcs);
        return $serial;
    }
    
    /**
     * convertToMEIDDec
     * Converts a specified or stored input to MEID Dec. Format
     * 
     * @param $input - Optional input to specify. Uses stored value if null.
     */
    public function convertMeidHexToDec(Serial\MEID $serial){
        if($serial->getInputType() == $serial::INPUT_DEC || $serial->getDecimal()){
        	return $serial->getDecimal();
        }     
        return $this->transformSerial($serial->getHexadecimal(), 16, 10, 8, 10, 8);
    }
    /**
     * convertToMEIDHex
     * Converts a specified or stored input to MEID HEX. Format
     * 
     * @param $input - Optional input to specify. Uses stored value if null.
     */
    public function convertMeidDecToHex(Serial\MEID $serial){
    	if($serial->getInputType() == $serial::INPUT_HEX || $serial->getHexadecimal()){
    		return $serial->getHexadecimal();
    	}
    	return $this->transformSerial($serial->getDecimal(), 10, 16, 10, 8, 6);
    }
    
    /**
     * convertToESNDec
     * Converts a specified or stored input to ESN DEC. Format
     * 
     * @param $input - Optional input to specify. Uses stored value if null.
     */
    public function convertEsnHexToDec(Serial\ESN $serial){
    	if($serial->getInputType() == $serial::INPUT_DEC || $serial->getDecimal()){
    		return $serial->getDecimal();
    	}
    	return $this->transformSerial($serial->getHexadecimal(), 16, 10, 2, 3, 8);
    }
    
    /**
     * convertToESNHex
     * Converts a specified or stored input to ESN HEX. Format
     * 
     * @param $input - Optional input to specify. Uses stored value if null.
     */
    public function convertEsnDecToHex(Serial\ESN $serial){
    	if($serial->getInputType() == $serial::TYPE_HEX || $serial->getHexadecimal()){
    		return $serial->getHexadecimal();
    	}
    	return $this->transformSerial($serial->getHexadecimal(), 10, 16, 3, 2, 6);
    }
    
    /**
     * calculatePesn
     * 
     * @return - The calculated pESN
     */
    public function calculatePESN(Serial\MEID $serial){
        $pESN = $serial->getPESN();
        
        if(! $input =  $serial->getHexadecimal()){
        	throw new \InvalidArgumentException('MEID does not have HEX set to calculate PESN');
        }
        
        $p = '';
        for ($i = 0; $i < strlen($input); $i += 2){
            $p .= chr(intval(substr($input, $i, 2), 16));
        }
        $hash = sha1($p);
        
        $pESN->setInput(strtoupper("80".substr($hash,(strlen($hash) -6))));
        $pESN->setDecimal($this->convertEsnHexToDec($pESN));
        return $pESN;
    }
    
    /**
     * transformSerial
     * 
     * @param string $n - The input
     * @param int $srcBase - The Source Base Size
     * @param int $dstBase - The Destination Base Size
     * @param int $p1Width - The Width of the First Part
     * @param int $p1Padding - The Padding for the First Part
     * @param int $p2Padding - The Padding for the Second Part
     * 
     * @return string - The transformed serial number
     */
    protected function transformSerial($n, $srcBase, $dstBase, $p1Width, $p1Padding, $p2Padding)
    {
        $p1 = $this->lPad(base_convert(substr($n,0,$p1Width),$srcBase,$dstBase),$p1Padding,0);
        
        $p2 = $this->lPad(base_convert(substr($n,$p1Width),$srcBase,$dstBase),$p2Padding,0);
        
        return strtoupper($p1.$p2);
    }
    
    /**
     * lPad
     * 
     * @param $s string - The input
     * @param $len int - Length
     * @param $p int - Padding
     */
    protected function lPad($s, $len, $p) 
    { 
        
        if($len <= strlen($s)){
            return $s;
        }
        return $this->lPad($p.$s, $len, $p);
    }
    
}