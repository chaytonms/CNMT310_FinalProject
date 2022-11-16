<?php
session_start();

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
    'coursename' => $_POST['coursename'], 
    'coursecode' => $_POST['coursecode'], 
    'coursenum' => $_POST['coursenum'], 
    'coursecredits' => $_POST['coursecredits'], 
    'coursedesc' => $_POST['coursedesc'],
    'courseinstr' => $_POST['courseinstr'],
    'meetingtimes' => $_POST['meetingtimes'],
    'maxenroll' => $_POST['maxenroll']
);

$unsetFields = $VW->AreSet(array_values($formFields));
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
             "data" => $formFields,
             "action" => "addcourse"
             );


$client->setPostFields($postData);
$json = (object) json_decode($client->send());

if ($json == null || !isset($json->result) || $json->result != "Success") { // might need more checks
    $_SESSION['errors'] = array("Error with adding class", $json);
    die(header("Location:addClass.php"));
} else { // ik this not needed but since they are linked logically I like to have it

    $_SESSION['successes'] = array("Success adding a class!");
    die(header("Location:dashboard.php"));
}
//print var_dump($json);

?>