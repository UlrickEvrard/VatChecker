<?php


class VatChecker{

    private $viesURL = "https://ec.europa.eu/taxation_customs/vies/checkVatTestService.wsdl";
    private $viesSOAP;

    private $viesHB;
    private $strict = true;

    /*
    *   You must specify the comportement of VatChecker when VES api is down.

    *   true == strict verification, if VIES is down, will return false.
    *   false == if VIES is down, return true ( doesn't block you customers because of VIES maintenance ).
    *
    *   @param type boolean
    **/
    function __construct($strict){
        
        $this->strict = $strict;
        $this->viesSOAP = new SoapClient($this->viesURL);

        $this-> viesHB = $this->hb_VIES();
    }

    /*
    * Check if VIES is UP, if not $viesHB will be false.
    **/
    function hb_VIES(){

        if(is_soap_fault($this->viesSOAP)){
           return true;
        }
        else{
            return false;
        }
    }

    /*
    * Check the vat number using vies first, and using mathematics if strict == false.
    *
    * @param type string
    * @param type int
    **/
    function VATcheck($isoVAT, $numVAT){

        $VATarray = array(
            'countryCode' => $isoVAT,
            'vatNumber' => $numVAT
          );

        if($this->viesHB){

            $this->VIEScheck($VATarray);
        }
        else if(!$this->strict){

            $this->MATHcheck($isoVAT, $numVAT);
        }
        else{

            return false;
        }
    }

    private function VIEScheck($VATarray){

        $result = $viesSOAP->checkVat($VATarray);
        var_dump($result);
    }
    
    private function MATHcheck($isoVAT, $numVAT){

    }
}
?>