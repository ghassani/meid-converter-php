<?php 
/**
 * @package MeidConverter
 * @author Gassan Idriss <ghassani@splicedmedia.com>
 * @website http://www.meidconverter.net - http://www.splicedmedia.com
 * @copyright 2011 Spliced Media L.L.C
 * 
 */

class MeidConverter{

    /* REGEXP EXPRESSIONS FOR INPUT VALIDATION */
    const VALIDATE_MEID_DEC     = '/[0-9]{18}/';
    const VALIDATE_MEID_HEX     = '/[a-fA-F0-9]{14}/';
    const VALIDATE_ESN_DEC      = '/[0-9]{11}/';
    const VALIDATE_ESN_HEX      = '/[a-fA-F0-9]{8}/';
    /* VALIDATED INPUT TYPE CONSTANTS */
    const INPUT_MEID_DEC    = 'MEID_DEC';
    const INPUT_MEID_HEX    = 'MEID_HEX';
    const INPUT_ESN_DEC     = 'ESN_DEC';
    const INPUT_ESN_HEX     = 'ESN_HEX';
    
    /* @param string $input - The stored user input */
    protected $input = null;
    /* @param string $input - The determined user input type constant value */
    protected $inputType = null;
    
    /* @param bool $isMEID - True for MEID false for ESN */
    protected $isMEID;
    /* @param bool $isDEC - True for DEC false for HEX */
    protected $isDEC;
    
    /**
     * @function __construct
     * Returns a new Instance of this class
     * 
     * @param $input string - Optional input value to start with, or you can set it later
     * @return MeidConverter
     */
    public function __construct($input=null){
        if(!is_null($input)){
            $this->setInput($input);
        }
        $this->conversionValues = array(
            self::INPUT_MEID_DEC => null,
            self::INPUT_MEID_HEX => null,
            self::INPUT_ESN_DEC  => null,
            self::INPUT_ESN_HEX  => null,
        );
        return $this;
    }
    /**
     * @function setInput
     * Sets the input. It is then validated. 
     * 
     * @param string $input - The input to calculate
     * @throws Exception - When not validated
     * @return MeidConverter
     */
    public function setInput($input){
        if(preg_match(self::VALIDATE_MEID_DEC,$input)){
            $this->inputType = self::INPUT_MEID_DEC;
            $this->isMEID = true;
            $this->isDEC = true;
        } else if(preg_match(self::VALIDATE_MEID_HEX,$input)){
            $this->inputType = self::INPUT_MEID_HEX;
            $this->isMEID = true;
            $this->isDEC = false;
        } else if(preg_match(self::VALIDATE_ESN_DEC,$input)){
            $this->inputType = self::INPUT_ESN_DEC;
            $this->isMEID = false;
            $this->isDEC = true;
        } else if(preg_match(self::VALIDATE_ESN_HEX,$input)){
            $this->inputType = self::INPUT_ESN_HEX;
            $this->isMEID = false;
            $this->isDEC = false;
        } else {
            throw new Exception('Could Not Validate Your Input');
            return $this;
        }
        if($input != $this->getInput()){
            $this->conversionValues = array(
                self::INPUT_MEID_DEC => null,
                self::INPUT_MEID_HEX => null,
                self::INPUT_ESN_DEC => null,
                self::INPUT_ESN_HEX => null,
            );
        }
        $this->input = $input;
        return $this;
    }
    /**
     * getInput
     * 
     * @return The stored user input
     * 
     */
    public function getInput(){
        return $this->input;
    }
    
    /**
     * Converts the specified input
     * 
     * @return array - An array of key value conversion values
     */
    public function convert(){        
        $this->conversionValues = array(
            self::INPUT_MEID_DEC => $this->convertToMEIDDec(),
            self::INPUT_MEID_HEX => $this->convertToMEIDHex(),
            self::INPUT_ESN_HEX => $this->convertToESNHex(),
            self::INPUT_ESN_DEC => $this->convertToESNDec(),
        );
        return $this->conversionValues;
    }
    
    /**
     * convertToMEIDDec
     * Converts a specified or stored input to MEID Dec. Format
     * 
     * @param $input - Optional input to specify. Uses stored value if null.
     */
    public function convertToMEIDDec($input=null){
        if(is_null($input)){
            $input = $this->getInput();
        }
        
        if($input != $this->getInput()){
            $this->setInput($input);
        }
        
        if(!empty($this->conversionValues[self::INPUT_MEID_DEC])){
            return $this->conversionValues[self::INPUT_MEID_DEC];
        }
        
        if($this->isMEID && $this->isDEC){
            $this->conversionValues[self::INPUT_MEID_DEC] = $input;
            return $input;
        }
        
        if(!$this->isMEID){
            return null;
        }
        
        $result = $this->transformSerial($input, 16, 10, 8, 10, 8);
        $this->conversionValues[self::INPUT_MEID_DEC] = $result;
        return $result;
    }
    /**
     * convertToMEIDHex
     * Converts a specified or stored input to MEID HEX. Format
     * 
     * @param $input - Optional input to specify. Uses stored value if null.
     */
    public function convertToMEIDHex($input=null){
        if(is_null($input)){
            $input = $this->getInput();
        }
        
        if($input != $this->getInput()){
            $this->setInput($input);
        }
        
        if(!empty($this->conversionValues[self::INPUT_MEID_HEX])){
            return $this->conversionValues[self::INPUT_MEID_HEX];
        }
        
        if($this->isMEID && !$this->isDEC){
            $this->conversionValues[self::INPUT_MEID_HEX] = $input;
            return $input;
        }
        
        if(!$this->isMEID){
            return null;
        }
        
        $result = $this->transformSerial($input, 10, 16, 10, 8, 6);
        $this->conversionValues[self::INPUT_MEID_HEX] = $result;
        return $result;
    }
    /**
     * convertToESNDec
     * Converts a specified or stored input to ESN DEC Format
     * 
     * @param $input - Optional input to specify. Uses stored value if null.
     */
    public function convertToESNDec($input=null){
        if(is_null($input)){
            $input = $this->getInput();
        }
        
        if($input != $this->getInput()){
            $this->setInput($input);
        }
        
        if(!empty($this->conversionValues[self::INPUT_ESN_DEC])){
            return $this->conversionValues[self::INPUT_ESN_DEC];
        }
        
        if(!$this->isMEID && $this->isDEC){
            $this->conversionValues[self::INPUT_ESN_DEC] = $input;
            return $input;
        }
        
        if($this->isMEID){
            $pESN = $this->calculatePESN();
            $result = $this->transformSerial($pESN, 16, 10, 2, 3, 8);
            $this->conversionValues[self::INPUT_ESN_DEC] = $result;
            return $result;
        }
        

        $result = $this->transformSerial($input, 16, 10, 2, 3, 8);
        $this->conversionValues[self::INPUT_ESN_DEC] = $result;
        return $result;
    }
    /**
     * convertToESNHex
     * Converts a specified or stored input to ESN HEX. Format
     * 
     * @param $input - Optional input to specify. Uses stored value if null.
     */
    public function convertToESNHex($input=null){
        if(is_null($input)){
            $input = $this->getInput();
        }
        
        if($input != $this->getInput()){
            $this->setInput($input);
        }
        
        if(!empty($this->conversionValues[self::INPUT_ESN_HEX])){
            return $this->conversionValues[self::INPUT_ESN_HEX];
        }
        
        if($this->isMEID){
            $this->conversionValues[self::INPUT_ESN_HEX] = $this->calculatePESN();
            return $this->conversionValues[self::INPUT_ESN_HEX];
        }
        
        if(!$this->isMEID && !$this->isDEC){
            $this->conversionValues[self::INPUT_ESN_HEX] = $input;
            return $input;
        }
        
        $result = $this->transformSerial($input, 10, 16, 3, 2, 6);
        $this->conversionValues[self::INPUT_ESN_HEX] = $result;
        return $result;
    }
    
    /**
     * calculatePesn
     * 
     * @return - The calculated pESN
     */
    protected function calculatePesn(){
        if(!$this->isMEID){ //ESN has no pESN
            return null;
        }
        
        $input = $this->convertToMEIDHex();
        
        $p = '';
        for ($i = 0; $i < strlen($input); $i += 2){
            $p .= chr(intval(substr($input, $i, 2), 16));
        }
        $hash = sha1($p);
        return strtoupper("80".substr($hash,(strlen($hash) -6)));
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
        return strtoupper(
            $this->lPad(base_convert(substr($n,0,$p1Width),$srcBase,$dstBase),$p1Padding,0).
            $this->lPad(base_convert(substr($n,$p1Width),$srcBase,$dstBase),$p2Padding,0)
        );
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