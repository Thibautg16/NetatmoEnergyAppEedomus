<?php
# Copyright (C) 2017-2018 @Thibautg16
# This file is part of NetatmoThermostatApp <https://github.com/Thibautg16/NetatmoEnergyAppEedomus>.
#
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with This program. If not, see <http://www.gnu.org/licenses/>.
#

#### variables ####
$MODE_NETATMO = 'netatmo'; // Dans ce mode, on met une consigne temporaire
$MODE_EEDOMUS = 'eedomus'; // Dans ce mode, on maintient la température de consigne
$CACHE_DURATION = 2; // minutes
$is_action = false; // Action ou mode capteur ?
$code = getArg('oauth_code');
$home_id = $_GET['home_id'];
$room_id = $_GET['room_id'];
$prev_code = loadVariable('code');

#### urls ####
$api_url = 'https://api.netatmo.com';
$url_homesdata = $api_url.'/api/homesdata';
$url_homesdata_id = $api_url.'/api/homesdata?home_id='.$home_id;
$url_homestatus = $api_url.'/api/homestatus?home_id='.$home_id;
################################################################

##### function #####
function sdk_get_query_and_check_error($url, $headers, $result = TRUE, $error = TRUE){
	$query = httpQuery($url, 'GET' , NULL, NULL, $headers);	
	$json = sdk_json_decode($query);

	//invalid token, on force l'expiration pour la fois suivante
	if ($json['error']['code'] == 2){
		saveVariable('expire_time', 0);
		die("Erreur lors de la lecture des devices #2: <b>".$json['error']['message'].'</b>');
	}

	if ($json['error'] != ''){
		$erreur = "//!\\ Erreur //!\\ Code: ".$json['error']['code']." / Message: ".$json['error']['message'];
		if ($error == TRUE){
			die($erreur);
		}
		else {
			return $erreur;
		}
	}	

	if ($result == TRUE ){
		return $json;
	} 
	else {
		return "ok";
	}
}

function sdk_netatmo_html($extension_module){
	$ret = '';
	$extension_module = $extension_module['body']['homes'];

	if(sizeof($extension_module) > 0){
		$ret .= '<br>';
		$ret .= '<br>';
		$ret .= "Voici la liste de vos maisons et leurs pièces associées :";
		$ret .= '<br>';
		$ret .= '<ul>';
		//homes informations
		for ($i = 0; $i < sizeof($extension_module); $i++){
			$ret .= '<li>'.str_replace(' ', '_', $extension_module[$i]['name']).': <input onclick="this.select();" type="text" size="17" readonly="readonly" value="'.$extension_module[$i]['id'].'"</li>';
			//rooms informations 
			$ret .= '<ul>';
			for ($j = 0; $j < sizeof($extension_module[$i]['rooms']); $j++){
				$ret .= '<li>'.str_replace(' ', '_', $extension_module[$i]['rooms'][$j]['name']).': <input onclick="this.select();" type="text" size="17" readonly="readonly" value="'.$extension_module[$i]['rooms'][$j]['id'].'"</li>';
			}
			$ret .= '</ul>';
		}
		$ret .= '</ul>';
	}

	return $ret;
}

function sdk_netatmo_set_room_temperature($home_id, $room_id, $mode, $temperature, $delay = 60, $headers){
	$endtime;
	if($mode == $GLOBALS['MODE_EEDOMUS'])
	{
		$endtime = time() + 12 /*heures*/ * 3600;
	}
	else if($mode == $GLOBALS['MODE_NETATMO'])
	{
		$endtime = time() + $delay /*minutes*/ * 60;
	}
	$url = $GLOBALS['api_url'].'/api/setroomthermpoint?home_id='.$home_id.'&room_id='.$room_id.'&mode=manual&temp='.$temperature.'&endtime='.$endtime;
	return sdk_get_query_and_check_error($url, $headers, FALSE, FALSE);
}

function sdk_netatmo_compute_rf_strength($rf_strength){
	if ($rf_strength >= 85){
		$rf_status = "low";
	}
	else if($rf_strength >= 75 && $rf_strength <= 84){
		$rf_status = "medium";
	}
	else if($rf_strength >= 65 && $rf_strength <= 74){
		$rf_status = "high";
	}
	else if($rf_strength <= 64){
		$rf_status = "full";
	}	
	
	return '<rf_strength>'.$rf_status.'</rf_strength>';
}

function sdk_netatmo_compute_wifi_strength($wifi_strength){
	if ($wifi_strength >= 72){
		$wifi_status = "poor";
	}
	else if($wifi_strength <= 71){
		$wifi_status = "good";
	}

	return '<wifi_strength>'.$wifi_status.'</wifi_strength>';
}

function sdk_netatmo_compute_boiler_status($boiler_information){
	if ($boiler_information['anticipating'] == TRUE){
		$boiler = "anticipating";
	}
	else if ($boiler_information['boiler_valve_comfort_boost'] == TRUE){
		$boiler = "valve_boost";
	}
	else if ($boiler_information['boiler_status'] == TRUE){
		$boiler = "on";
	}
	else {
		$boiler = "off";
	}
	return $boiler;
}
################################################################

##### check cache #####
$setpoint_mode = $_GET['setpoint_mode'];
$setpoint_temp = $_GET['setpoint_temperature'];
if ($setpoint_mode != '' || $setpoint_temp != ''){
	$is_action = true;
}

$last_xml_success = loadVariable('last_xml_success');
$time_from_last = time() - $last_xml_success;
$time_from_last = $time_from_last / 60;
if (!$is_action && $_GET['mode'] != 'verify' && $time_from_last < $CACHE_DURATION){
	sdk_header('text/xml');
	$cached_xml = loadVariable('cached_xml');
	echo $cached_xml;
	die();
}
################################################################

##### recuperation acces_token #####
if (strlen($prev_code) > 1 && $code == $prev_code){
	// on reprend le dernier refresh_token seulement s'il correspond au même code
	$refresh_token = loadVariable('refresh_token');
	$expire_time = loadVariable('expire_time');
	// s'il n'a pas expiré on peut reprendre l'access_token
  	if (time() < $expire_time){
    	$access_token = loadVariable('access_token');
  	}
}

// on a déjà un token d'accès non expiré pour le code demandé
if ($access_token == ''){
	if (strlen($refresh_token) > 1){
		// on peut juste rafraichir le token
		$grant_type = 'refresh_token';
		$postdata = 'grant_type='.$grant_type.'&refresh_token='.$refresh_token;
	}
	else{
		// 1ére utilisation après obtention du code
		$grant_type = 'authorization_code';
		$redirect_uri = 'https://secure.eedomus.com/sdk/plugins/netatmo_thermostat/callback.php';
		$scope = 'read_thermostat write_thermostat';
		$postdata = 'grant_type='.$grant_type.'&code='.$code.'&redirect_uri='.$redirect_uri.'&scope='.$scope;
	}

	$response = httpQuery($api_url.'/oauth2/token', 'POST', $postdata, 'netatmo_thermostat_oauth');
	$params = sdk_json_decode($response);

	if ($params['error'] != ''){
		die("Erreur lors de l'authentification: <b>".$params['error'].'</b> (grant_type = '.$grant_type.')');
	}

	// on sauvegarde l'access_token et le refresh_token pour les authentifications suivantes
	if (isset($params['refresh_token'])){
		$access_token = $params['access_token'];
		saveVariable('access_token', $access_token);
		saveVariable('refresh_token', $params['refresh_token']);
		saveVariable('expire_time', time()+$params['expires_in']);
		saveVariable('code', $code);
	}
	else if ($access_token == ''){
		die("Erreur lors de l'authentification");
	}
}

// on set le token dans les headers
$headers = array("Authorization: Bearer ".$access_token);
################################################################

##### information lors de la récupération du code oauth #####
if ($_GET['mode'] == 'verify'){
	?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
  	<head>
  	<title>eedomus</title>
  	<style type="text/css">
  
  	body,td,th {
    	font-family: Arial, Helvetica, sans-serif;
    	font-size: 14px;
  	}
  	</style>
  	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
  	</head><?
  	echo '<br>';
	echo "Votre code d'authentification Netatmo est : ".'<input onclick="this.select();" type="text" size="40" readonly="readonly" value="'.$code.'" />';

	//homes data	
	$json_homesdata = sdk_get_query_and_check_error($url_homesdata, $headers);
	
	echo sdk_netatmo_html($json_homesdata);

	echo '<br>';
	echo "Vous pouvez copier/coller ces informations dans le paramétrage de votre périphérique Eedomus.";

	die();
}
################################################################

##### corp du script #####
################################################################
if($home_id == ''){
	sdk_header('text/xml');
	$xml = '<?xml version="1.0" encoding="utf8" ?>';
	$xml .= '<netatmo>';
	$xml .= '<status>';
	$xml .= 'home_id missing';
	$xml .= '</status>';
	$xml .= '</netatmo>';
	echo $xml;
	die();
}

// Les critères sont réunis pour demander d'effectuer une action
if ($is_action){
	// Valeurs possibles pour le mode
	$setpoint_mode_home_valid_values = array('away', 'hg', 'schedule');
	$setpoint_mode_room_valid_valies = array('home');

	// XML de sortie
	sdk_header('text/xml');
	$xml = '<?xml version="1.0" encoding="utf8" ?>';
	$xml .= '<netatmo>';
	$xml .= '<status>';

	// On va passer en mode manuel et forcer une température
	if($setpoint_temp != ''){
		$mode;
		$maintain_setpoint = $_GET['maintain_setpoint'];
		if($maintain_setpoint == "always"){
			$mode = $MODE_EEDOMUS;
			saveVariable('maintain_mode', $MODE_EEDOMUS);
		}
		else{
			$mode = $MODE_NETATMO;
			saveVariable('maintain_mode', $MODE_NETATMO);
		}

		$xml .= sdk_netatmo_set_room_temperature($home_id, $room_id, $mode, $setpoint_temp, $maintain_setpoint, $headers);
	}
	// On va changer le mode de la room
	else if(in_array($setpoint_mode, $setpoint_mode_room_valid_valies)){
		$url = $api_url.'/api/setroomthermpoint?home_id='.$home_id.'&room_id='.$room_id.'&mode='.$setpoint_mode;
		$xml .= sdk_get_query_and_check_error($url, $headers, FALSE, FALSE);
	}
	// On va seulement changer de mode sans modifier la température
	else if(in_array($setpoint_mode, $setpoint_mode_home_valid_values)){
		$url = $api_url.'/api/setthermmode?home_id='.$home_id.'&mode='.$setpoint_mode;
		$xml .= sdk_get_query_and_check_error($url, $headers, FALSE, FALSE);
	}
	else{
		$xml .= 'ko';
	}

	$xml .= '</status>';
	$xml .= '</netatmo>';
	echo $xml;
}
// On effectue une lecture des données
else{
	//variables
	$modules_name = array();
	$rooms_name = array();

    //home status
	$json_homestatus = sdk_get_query_and_check_error($url_homestatus, $headers);
	//homes data	
	$json_homesdata = sdk_get_query_and_check_error($url_homesdata_id, $headers);

	//modules and rooms name
	for ($i = 0; $i < sizeof($json_homesdata['body']['homes']); $i++){
		if ($json_homesdata['body']['homes'][$i]['id'] == $home_id) {
			//modules
			for ($j = 0; $j < sizeof($json_homesdata['body']['homes'][$i]['modules']); $j++){
				$modules_name[$json_homesdata['body']['homes'][$i]['modules'][$j]['id']] = str_replace(' ', '_', $json_homesdata['body']['homes'][$i]['modules'][$j]['name']);
			}
			//rooms
			for ($j = 0; $j < sizeof($json_homesdata['body']['homes'][$i]['rooms']); $j++){
				$rooms_name[$json_homesdata['body']['homes'][$i]['rooms'][$j]['id']] = str_replace(' ', '_', $json_homesdata['body']['homes'][$i]['rooms'][$j]['name']);
			}			
		}
	}

	//get rooms informations
	for ($i = 0; $i < sizeof($json_homestatus['body']['home']['rooms']); $i++){
		//rooms informations
		$rooms .= '<room_'.$rooms_name[$json_homestatus['body']['home']['rooms'][$i]['id']].'>';
		$rooms .= '<therm_measured_temperature>'.$json_homestatus['body']['home']['rooms'][$i]['therm_measured_temperature'].'</therm_measured_temperature>';
		$rooms .= '<therm_setpoint_temperature>'.$json_homestatus['body']['home']['rooms'][$i]['therm_setpoint_temperature'].'</therm_setpoint_temperature>';
		$rooms .= '<therm_setpoint_mode>'.$json_homestatus['body']['home']['rooms'][$i]['therm_setpoint_mode'].'</therm_setpoint_mode>';
		$rooms .= '</room_'.$rooms_name[$json_homestatus['body']['home']['rooms'][$i]['id']].'>';
	}

	//get modules informations 
	for ($i = 0; $i < sizeof($json_homestatus['body']['home']['modules']); $i++){
		$modules .= '<module_'.$modules_name[$json_homestatus['body']['home']['modules'][$i]['id']].'>';
		$modules .= '<type>'.$json_homestatus['body']['home']['modules'][$i]['type'].'</type>';
		$modules .= sdk_netatmo_compute_rf_strength($json_homestatus['body']['home']['modules'][$i]['rf_strength']);		
		
		//specific information NAPlug
		if ($json_homestatus['body']['home']['modules'][$i]['type'] == "NAPlug"){
			$modules .= sdk_netatmo_compute_wifi_strength($json_homestatus['body']['home']['modules'][$i]['wifi_strength']);
		} 
		else if($json_homestatus['body']['home']['modules'][$i]['type'] == "NATherm1"){
			$modules .= '<reachable>'.(int)$json_homestatus['body']['home']['modules'][$i]['reachable'].'</reachable>';
			$modules .= '<boiler_status>'.sdk_netatmo_compute_boiler_status($json_homestatus['body']['home']['modules'][$i]).'</boiler_status>';
			$modules .= '<anticipating>'.(int)$json_homestatus['body']['home']['modules'][$i]['anticipating'].'</anticipating>';
			$modules .= '<battery_state>'.$json_homestatus['body']['home']['modules'][$i]['battery_state'].'</battery_state>';
		} 		
		else {
			$modules .= '<reachable>'.(int)$json_homestatus['body']['home']['modules'][$i]['reachable'].'</reachable>';
			$modules .= '<battery_state>'.$json_homestatus['body']['home']['modules'][$i]['battery_state'].'</battery_state>';
		}

		//fin module
		$modules .= '</module_'.$modules_name[$json_homestatus['body']['home']['modules'][$i]['id']].'>';
	}

	// XML de sortie
	sdk_header('text/xml');
	
	// Contenu du XML
	$cached_xml = '<?xml version="1.0" encoding="utf8" ?>';
	$cached_xml .= '<netatmo>';
	$cached_xml .= '<cached>0</cached>';
	
	if (isset($setpoint_temperature) && $setpoint_mode == 'manual'){
		// Température manuellement imposée
		$cached_xml .= '<setpoint_temperature>'.$setpoint_temperature.'</setpoint_temperature>';

		// Vérification: besoin de l'imposer à nouveau ?
		// Oui si on est en mode eedomus et proche du temps imparti
		$maintain_mode = loadVariable('maintain_mode');
		if($maintain_mode != $MODE_NETATMO){
			$now = time();
			$setpoint_endtime = $json_devices['body']['devices'][0]['modules'][0]['setpoint']['setpoint_endtime'];
			if($setpoint_endtime - $now < 3600){
				sdk_netatmo_set_room_temperature($setpoint_temperature, $MODE_EEDOMUS);
			}
		}
	}
	
	if (isset($temperature)){
    	$allow_cache = true;
		$cached_xml .= '<temperature>'.$temperature.'</temperature>';
	}
	else {
    	$allow_cache = false;
	}
	
	if(isset($rooms)){
		$cached_xml .= $rooms;
	}
	if(isset($modules)){
		$cached_xml .= $modules;
	}	

	//$cached_xml .= '<token>'.$access_token.'</token>';
	$cached_xml .= '</netatmo>';

	echo $cached_xml;
	$cached_xml = str_replace('<cached>0</cached>', '<cached>1</cached>', $cached_xml);
	if ($allow_cache){
    	saveVariable('cached_xml', $cached_xml);
    	saveVariable('last_xml_success', time());
	}
}
?>
