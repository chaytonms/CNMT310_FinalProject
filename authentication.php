<?php
require_once("WebServiceClient.php");
require_once("Template.php");

if (!isset($_POST['username']) || !isset($_POST['password'])) {
    //die(header("Location: index.php"));
    var_dump($_POST);
}

$url = "http://cnmt310.classconvo.com/classreg/";
$client = new WebServiceClient($url);
$template = new Template("Auth");

//Default is to POST. If you need to change to a GET, here's how:
$client->setMethod("GET");


$apihash = "fsfeguphgf"; // todo add into env
$apikey = "api6"; // todo add into env
$action = "authenticate";
$zip = "54481";

// todo turn into form
$username = $_POST["username"];
$password = $_POST['password'];

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
print var_dump(json_decode($client->send()));
print $template->closeHTML();

?>