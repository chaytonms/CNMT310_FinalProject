<?php
session_start();

require_once(__DIR__.'/../WebServiceClient.php');
require_once("../ValidationWizard.php");
require_once(__DIR__.'/../const.php');
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

if (!isset($_POST) || !isset($_SESSION)) {
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

print var_dump($_POST);

if (!isset($_POST['coursename'], $_POST['coursecode'], 
    $_POST['coursenum'], $_POST['coursecredits'], $_POST['coursedesc'],
    $_POST['courseinstr'], $_POST['meetingtimes'], $_POST['maxenroll']) ||
    $VW->AreEmpty(array_values($formFields))) {

    $_SESSION['errors'] = array("Make sure all fields are entered");
    die(header("Location:addClass.php"));
}


if (!isset($_SESSION['user'])) {

    $_SESSION['errors'] = array("Session Error");
    die(header("Location:index.php"));        
}

// make the request to the api
$url = "http://cnmt310.classconvo.com/classreg/";
$client = new WebServiceClient($url);

$postData = array("apikey" => APIKEY,
             "apihash" => APIHASH,
             "data" => $formFields,
             "action" => "addcourse"
             );


$client->setPostFields($postData);
$json = (object) json_decode($client->send());

if ($json == null || !isset($json->result) || $json->result != "Success") { // might need more checks
    $_SESSION['errors'] = array("Error with adding class", json_encode($json));
    die(header("Location:addClass.php"));
} else { // ik this not needed but since they are linked logically I like to have it
    unset($_SESSION['errors']);
    $_SESSION['successes'] = array("Success adding a class!");
    die(header("Location:dashboard.php"));
}
//print var_dump($json);

?>