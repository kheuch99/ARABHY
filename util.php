<?php 
define("API_KEY", "1dad5d9cec1943319a0130207170606");

function getPastWeather($city, $country, $nbDays) {
    //~ http://api.worldweatheronline.com/premium/v1/past-weather.ashx?key=xxxxxxxxxxxxxxxxx&q=SW1&date=2009-07-20&format=xml
	
	//*********************************** Query ***********************************
	//Can be city, state, country, zip/postal code, IP address, longtitude/latitude. 
	//If long/lat are 2 elements, they will be assembled. IP address is one element.
	$loc_array = Array($city, $country);	
	
	//Should be embedded in your code, so no data validation necessary, otherwise if(strlen($api_key)!=24)
	$api_key = API_KEY;			
		
	$date    = date("Y-m-d", strtotime("-" . $nbDays . " days"));	//Validated as $date_safe
	$enddate = date("Y-m-d", strtotime("yesterday"));				//Validated as $enddate_safe
	
	//*********************************** Validation ***********************************
	//Validation of the q parameter 
	$loc_safe = Array();
	foreach($loc_array as $loc){
		$loc_safe[] = urlencode($loc);
	}
	$loc_string = implode(",", $loc_safe);
	
	//Validation of the dates. This SHOULD return the same value, but if malformed this will correct 
	$date_safe    = urlencode($date);
	$enddate_safe = urlencode($enddate);
	
	//*********************************** Request & Response ***********************************
	//To add more conditions to the query, just lengthen the url string
	$pastWeatherURL = sprintf('http://api.worldweatheronline.com/premium/v1/past-weather.ashx?key=%s&q=%s&date=%s&enddate=%s&format=xml&tp=1', 
		         	  		  $api_key, $loc_string, $date_safe, $enddate_safe);
	
	print $pastWeatherURL . "<br />";
	
	//Request and response retrievement
	$responseXML = file_get_contents($pastWeatherURL);
	$pastWeather = simplexml_load_string($responseXML)->weather;
	
	//die(var_dump($pastWeather));
	$precipitations = array();
	foreach($pastWeather as $w){
		$date   = $w->date;
		$hourly = $w->hourly;
		$precip = 0.0;
		foreach($hourly as $h){
			$precip += floatval($h->precipMM);
		}
		
		$precipitation = array("Date" => $date, "Precip" => $precip);
		array_push($precipitations, $precipitation);
	}
	
	return $precipitations;
}


function getWeather($city, $country, $date) {
    //~ http://api.worldweatheronline.com/free/v1/weather.ashx?key=xxxxxxxxxxxxxxxxx&q=SW1&num_of_days=3&format=xml

	//*********************************** Query ***********************************
	//Can be city, state, country, zip/postal code, IP address, longtitude/latitude. 
	//If long/lat are 2 elements, they will be assembled. IP address is one element.
	$loc_array = Array($city, $country);		
	
	//Should be embedded in your code, so no data validation necessary, otherwise if(strlen($api_key)!=24)
	$api_key = API_KEY;	
	
	//*********************************** Validation ***********************************
	//Validation of the q parameter 
	$loc_safe = Array();
	foreach($loc_array as $loc){
		$loc_safe[] = urlencode($loc);
	}
	$loc_string = implode(",", $loc_safe);		
	
	//Validation of the dates. This SHOULD return the same value, but if malformed this will correct 
	$date_safe = urlencode($date);
	
	//*********************************** Request & Response ***********************************
	//To add more conditions to the query, just lengthen the url string
	$weatherURL = sprintf('http://api.worldweatheronline.com/premium/v1/weather.ashx?key=%s&q=%s&date=%s&tp=1', 
						  $api_key, $loc_string, $date_safe);
	
	print $weatherURL . "<br />";
	
	$xml_response = file_get_contents($weatherURL);
	$weather 	  = simplexml_load_string($xml_response)->weather;		//TODO: change variable name
	
	$weatherDate  = $weather->date;
	$hourly       = $weather->hourly;
	$precip       = 0.0;
	foreach($hourly as $h){
		$precip += floatval($h->precipMM);
	}
	
	$precipitation = array("Date" => $weatherDate, "Precip" => $precip);
	
	return $precipitation;
}



function getPostWeather($city, $country, $nbDays) {
	
	$precipitations = array();
	
	for ($x = 1; $x <= $nbDays; $x++) {
	    $date = date("Y-m-d", strtotime("+" . $x . " days"));
	    $precipitation = getWeather($city, $country, $date);
	    array_push($precipitations, $precipitation);
	} 
	
	return $precipitations;
}
		
?>