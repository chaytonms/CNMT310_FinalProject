<?php
session_start();
require_once(__DIR__.'/../WebServiceClient.php');

if (!isset($_POST['username']) || !isset($_POST['password'])) {
    die(header("Location: index.php"));
    //var_dump($_POST);
}

if (!isset($_SESSION['user'])) { // this is to ensure they go through the index php page, which sets a default here
                                 // nonguests will overwrite this field with their class data.
    $_SESSION['errors'] = array("Please log in.");
    die(header("Location: index.php"));
}

$url = "http://cnmt310.classconvo.com/classreg/";
$client = new WebServiceClient($url);


//Default is to POST. If you need to change to a GET, here's how:
$client->setMethod("GET");


$apihash = "fsfeguphgf"; // todo add into env
$apikey = "api6"; // todo add into env
$action = "authenticate";

// todo turn into form
$username = $_POST["username"];
$password = $_POST['password'];

$data = array("apikey" => $apikey,
             "apihash" => $apihash,
             "data" => array("username" => $username, "password" => $password),
             "action" => $action
             );

$client->setPostFields($data);
$json = (object) json_decode($client->send());

// checks to ensure that it was a success
if ($json-> result != "Success" || !isset($json->result) || !isset($json->data) || !isset($json->data->user_role)) {
    $_SESSION['errors'] = array("Account not found");
    die(header("Location: index.php"));
}

$_SESSION['user'] = json_encode($json->data);
$_SESSION['apihash'] = $apihash; // todo remove once env is added
$_SESSION['apikey'] = $apikey; // todo ^

//print var_dump($_SESSION['user']);

die(header("Location: dashboard.php"));
?>