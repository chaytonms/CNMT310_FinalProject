<?php
session_start();
require_once(__DIR__.'/../WebServiceClient.php');
require_once(__DIR__.'/../const.php');
require_once(__DIR__.'/../ValidationWizard.php');

// ### data fields: ###
//    "coursename": "Principles of Computing", 
//     "coursecode": "CNMT", 
//     "coursenum": "100", 
//     "coursecredits": "3", 
//     "coursedesc": "Exploring the principles of computing", 
//    "courseinstr": "Simkins", 
//     "meetingtimes": "MW 11:00a-12:15p", 
//     "maxenroll": "24"

// Validation
if (!isset($_SESSION) || !isset($_SESSION['user'])) {
  session_error();
}

$user = json_decode($_SESSION['user']);

if (!isset($user->user_role)) {
  session_error();
}

if ($user->user_role != "admin") {
  forbidden_error();
}

if(!isset($_POST['coursename']) || empty($_POST['coursename'])
|| !isset($_POST['coursecode']) || empty($_POST['coursecode'])
|| !isset($_POST['coursenum']) || empty($_POST['coursenum'])
|| !isset($_POST['coursecredits']) || empty($_POST['coursecredits'])
|| !isset($_POST['coursedesc']) || empty($_POST['coursedesc'])
|| !isset($_POST['meetingtimes']) || empty($_POST['meetingtimes'])
|| !isset($_POST['maxenroll']) || empty($_POST['maxenroll'])
|| !isset($_POST['courseinstr']) || empty($_POST['courseinstr'])){
    $_SESSION['errors'] = array("Make sure all fields are entered.");
    die(header("Location: addClass.php"));
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

if ($json == null || !isset($json->result) || $json->result != "Success") {
    $_SESSION['errors'] = array("Error with adding class.");
    die(header("Location:addClass.php"));
} else {
    unset($_SESSION['errors']);
    $_SESSION['successes'] = array("Success adding a class!");
    die(header("Location:dashboard.php"));
}
?>