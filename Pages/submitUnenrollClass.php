<?php
/*
Page Description: POST version of Remove Student From Course for Admin users. Handles the webservice process of removing a student from a selected course (not a viewable page).
*/

session_start();
require_once(__DIR__.'/../WebServiceClient.php');
require_once(__DIR__.'/../const.php');
require_once(__DIR__.'/../ValidationWizard.php');

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

if (!isset($_SESSION['manage']) || !isset($_SESSION['manage']['name'])) {
    $_SESSION['errors'] = array("Select a class to manage.");
    die(header("Location: dashboard.php"));
}

if(!isset($_POST['student_id']) || empty($_POST['student_id']) || !isset($_POST['course_id']) || empty($_POST['course_id'])){
    $_SESSION['errors'] = array("Please confirm a student to remove before attempting to navigate to this page.");
    die(header("Location: removestudentfromclass.php"));
}

$course_id = $_POST['course_id'];
$student_id = $_POST['student_id'];
$url = "http://cnmt310.classconvo.com/classreg/";
$client = new WebServiceClient($url);

// Remove Student From Course
$postData = array("apikey" => APIKEY,
                 "apihash" => APIHASH,
                 "data" => array( "course_id" => $course_id,
                                  "student_id" => $student_id),
                 "action" => "delstudentfromcourse"
                 );

$client->setPostFields($postData);
$json = (object) json_decode($client->send());

if($json == null || !isset($json->result) || $json->result != "Success"){
    if (isset($json->data) && isset($json->data->message) && $json->result == "Error") {
        $_SESSION['errors'][] = $json->data->message;
        die(header("Location: manageclass.php"));
    }
    $_SESSION['errors'] = array("There was an error processing your request.");
    die(header("Location: dashboard.php"));
}
$_SESSION['successes'] = array("Successfully removed Student with ID: $student_id from " . $_SESSION['manage']['name'] . '.');

unset($_SESSION['errors']);
unset($_SESSION['manage']);
die(header("Location: dashboard.php"));
?>