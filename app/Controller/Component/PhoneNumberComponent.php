<?php
App::uses('Component', 'Controller');

class PhoneNumberComponent extends Component {


    public function startup(Controller $controller)
    {
        parent::startup($controller);
    }

    public function getCountriesByPrefixes() { 
        $filePath = WWW_ROOT . "files"; 
		$fileName = "countries and codes.csv";
		$importedCountries = fopen($filePath . DS . $fileName,"r");
		$countries=array();
		$count = 0;
		$options = array();
		while(!feof($importedCountries)){
		   $countries[] = fgets($importedCountries);
		   if($count > 0 && $countries[$count]){
               $countries[$count] = str_replace("\n", "", $countries[$count]);
               $explodedLine = explode(",", $countries[$count]);
               $options[trim($explodedLine[1])] = trim($explodedLine[0]);
    	   }
		   $count++;		   
		}
		return $options;
	}

	public function getCountries() {
	    $countriesPrefixes = $this->getCountriesByPrefixes();
        return array_combine(array_values($countriesPrefixes), array_values($countriesPrefixes));  
	}


}