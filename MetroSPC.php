<?php 
/**
 * @package MetroPcsSpcCalculator
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 * @website http://www.meidconverter.net - http://www.splicedmedia.com
 * @copyright 2011 Spliced Media L.L.C
 * 
 */
class MetroPcsSpcCalculator extends MeidConverter
{
    /** */
    const OUTPUT_METRO_SPC = 'METRO_SPC';
    /**
     * @function __construct
     * Returns a new Instance of this class
     * 
     * @param $input string - Optional input value to start with, or you can set it later
     * @return MetroSpcCalculator
     */
    public function __construct($input){
        parent::__construct($input);
        return $this;
    }
    
    /**
     * Converts the specified input and calculates the MetroPCS SPC
     * 
     * @return array - An array of key value conversion values
     */
    public function calculate(){        
        $this->conversionValues = array(
            self::INPUT_MEID_DEC    => $this->convertToMEIDDec(),
            self::INPUT_MEID_HEX    => $this->convertToMEIDHex(),
            self::INPUT_ESN_HEX     => $this->convertToESNHex(),
            self::INPUT_ESN_DEC     => $this->convertToESNDec(),
            self::OUTPUT_METRO_SPC  => $this->getMetroSpc(),
        );
        return $this->conversionValues;
    }
    
    /**
     * 
     */
    public function getMetroSpc(){
        if(isset($this->conversionValues[self::OUTPUT_METRO_SPC])&&!empty($this->conversionValues[self::OUTPUT_METRO_SPC])){
            return $this->conversionValues[self::OUTPUT_METRO_SPC];
        }
        $input = $this->convertToESNDec();
        $esnDec = str_split($input);
        $subSet = substr($input,8);
        $spc =  (pow(2, (5 + $esnDec[0] + $esnDec[1] + $esnDec[2]) ) -1) *(intval($subSet,10) + 199) *(23+$esnDec[3]+$esnDec[4]+$esnDec[5]+$esnDec[6]+$esnDec[7]+$esnDec[8]+$esnDec[9]+$esnDec[10]);
        return substr($spc,(strlen($spc)-6));
    }
}    