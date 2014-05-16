#!/usr/bin/php
<?php

foreach ($argv as $arg) {
  $e=explode("=",$arg);
  if(count($e)==2)
    $_GET[$e[0]]=$e[1];
  else    
    $_GET[$e[0]]=0;
}

if (key_exists("list",$_GET)) {
  echo($get_value);
  echo "\n";
  die();
}
if (key_exists("nodes",$_GET)) {
  echo(gethostname());
  echo "\n";
  die();
}
if (key_exists("version",$_GET)) {
  echo("munin node on ".gethostname()." version: 1.0.0 (munin-netatmo)\n");
  die();
}
if (key_exists("quit",$_GET)) {
  die();
}

require_once("Netatmo-API/NAApiClient.php");
require_once("Netatmo-API/Config.php");

$client = new NAApiClient($config);

$client->setVariable("username", $test_username);
$client->setVariable("password", $test_password);

$helper = new NAApiHelper();
try {
    $tokens = $client->getAccessToken();        
    
} catch(NAClientException $ex) {
    echo "An error happend while trying to retrieve your tokens\n";
    die();
}

// Retrieve User Info :
$user = $client->api("getuser", "POST");

$devicelist = $client->api("devicelist", "POST");

#print_r($devicelist);

$devicelist = $helper->SimplifyDeviceList($devicelist);

$last_mesures = $helper->GetLastMeasures($client,$devicelist);

$device=$devicelist["devices"][0];
$module=$device["modules"][0];

#print_r($devicelist);

if (key_exists("config",$_GET)) {
  echo "graph_title Temperatures\n";
  echo "graph_vlabel Temperature\n";
  echo "graph_info The Temperatures (all Modules)\n";
  echo "graph_scale no\n";
  echo "graph_category netatmo\n"; 
}

$color=7;
foreach($last_mesures[0]['modules'] as $module) {
  if (key_exists("Temperature",$module)) {
    if (key_exists("config",$_GET)) {
      echo $module['module_name']."Temperature.label ".$module['module_name']."\n";
      if (key_exists("CO2",$module)) {
        echo $module['module_name']."Temperature.warning 15:30\n";
        echo $module['module_name']."Temperature.critical 10:40\n";    
        echo $module['module_name']."Temperature.colour COLOUR".$color."\n";
      } else {
        echo $module['module_name']."Temperature.warning 3:30\n";
        echo $module['module_name']."Temperature.critical -5:40\n";    
        echo $module['module_name']."Temperature.colour COLOUR".$color."\n";    
      }
    } else {
      echo $module['module_name']."Temperature.value ".floatval($module['Temperature']);
      echo "\n";
    }
    $color++;
  }
}

#print_r($module);

?>
