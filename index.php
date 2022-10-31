<?php
// home page

require_once("Template.php");
require_once("WebServiceClient.php");

$template = new Template("Home");


$url = "http://cnmt310.classconvo.com/classreg/";
$client = new WebServiceClient($url);

//Default is to POST. If you need to change to a GET, here's how:
$client->setMethod("GET");


$apihash = "fsfeguphgf"; // todo add into env
$apikey = "api6"; // todo add into env
$action = "authenticate";
$zip = "54481";

// todo turn into form
$username = "nrosa";
$password = "cJphY65Y";

$data = array("apikey" => $apikey,
             "apihash" => $apihash,
             "data" => array("username" => $username, "password" => $password),
             "action" => $action
             );

$client->setPostFields($data);
//For Debugging:
//var_dump($client);

$template->addBodyElement("<p>Hello!</>");

print $template->beginHTML();
print $client->send();
print $template->closeHTML();
?>