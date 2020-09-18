<?php
class JFormRuleBoldID extends JFormRule{
	public function test(SimpleXMLElement $element, $value, $group = null, JRegistry $input = null, JForm $form = null)
    {
    	$array = explode("\n", $value);

    	foreach ($array as $key => $id) {

	    	if (!$this->startsWith($id,"MO") && !$this->startsWith($id,"iNat")){
		   		$element->attributes()->message = "Record ID <b>$id</b> invalid.<br /> Each Record ID must begin with MO or iNat" ;
		   		return false;
		   	}
		   	if (preg_match('/\s/', trim($id) )){
		   		$element->attributes()->message = "Record ID <b>$id</b> invalid.<br /> No spaces are allowed in ID" ;
		   		return false;
		   	}
		   	if (strlen(trim($id)) > 13){
		   		$element->attributes()->message = "Record ID too long, make sure each record is on a new line" ;
		   		return false;
		   	}
    	}
    	return true;

    }
    public function startsWith ($string, $startString) 
	{ 
	    $len = strlen($startString); 
	    return (strtolower(substr($string, 0, $len))) === strtolower($startString); 
	} 
  

}
