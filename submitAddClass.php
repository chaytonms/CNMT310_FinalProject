<?php
require_once(__DIR__.'/../WebServiceClient.php');
require_once("../ValidationWizard.php");
$VW = new ValidationWizard();

// ### data fields: ###
//    "coursename": "Principles of Computing", 
//     "coursecode": "CNMT", 
//     "coursenum": "100", 
//     "coursecredits": "3", 
//     "coursedesc": "Exploring the principles of computing", 
//    "courseinstr": "Simkins", 
//     "meetingtimes": "MW 11:00a-12:15p", 
//     "maxenroll": "24"  

if (count($VW->AreSet(array($_SESSION, $_POST))) > 0) {
    $_SESSION['errors'] = array("Session Error");
    die(header("Location:index.php"));
}

$formFields = array(
    $_POST['coursename'], 
    $_POST['coursecode'], 
    $_POST['coursenum'], 
    $_POST['coursecredits'], 
    $_POST['coursedesc'],
    $_POST['courseinstr'],
    $_POST['meetingtimes'],
    $_POST['maxenroll']
);

$unsetFields = $VW->AreSet($formFields);
if (count($unsetFields) > 0) {
    $_SESSION['errors'] = array_combine(array("the following required fields are not set:"), $unsetFields);
    die(header("Location:addClass.php"));
}

$sessionReqs = array(
    $_SESSION['apihash'],
    $_SESSION['apikey'],
    $_SESSION['user']
    
);

$unsetFields = $VW->AreSet($sessionReqs);
if (count($unsetFields) > 0) {
    $_SESSION['errors'] = array("Session Error");
    die(header("Location:index.php"));
}

// make the request to the api
$url = "http://cnmt310.classconvo.com/classreg/";
$client = new WebServiceClient($url);

$postData = array("apikey" => $_SESSION['apikey'],
             "apihash" => $_SESSION['apihash'],
             "data" => array("username" => $username, "password" => $password),
             "action" => "addcourse"
             );


$client->setPostFields($data);
$json = (object) json_decode($client->send());



?>