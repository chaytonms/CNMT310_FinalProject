<?php
/*
Page Description: POST version of Add Student To Course for Admin users. Handles the webservice process of adding a student to a selected course (not a viewable page).
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

if(!isset($_POST['student_id']) || empty($_POST['student_id']) || !isset($_POST['course_id']) || empty($_POST['course_id'])
|| !isset($_POST['max']) || empty($_POST['max'])){
    $_SESSION['errors'] = array("Please confirm a student to add to the class before attempting to navigate to this page.");
    die(header("Location: removestudentfromclass.php"));
}

$max = $_POST['max'];
$course_id = $_POST['course_id'];
$student_id = $_POST['student_id'];
$url = "http://cnmt310.classconvo.com/classreg/";
$client = new WebServiceClient($url);

// Get Student Count
$postData = array("apikey" => APIKEY,
                  "apihash" => APIHASH,
                  "data" => array( "course_id" => $course_id ),
                  "action" => "getstudentlistbycourse"
                );

$client->setPostFields($postData);
$json = (object) json_decode($client->send());

if($json == null || !isset($json->result) || !isset($json->data) || $json->result != "Success"){
    if(!isset($json->data) && !isset($json->data->message) && $json->result != "Error"){
        $_SESSION['errors'] = array("There was an error processing your request.");
        die(header("Location: manageclass.php"));
    }
}

// Check if there is room in course before attempting to add student to the course
$currentEnrolled;
if($json->result != "Success"){
    $currentEnrolled = 0;
} else {
    $currentEnrolled = count($json->data);
}

if($currentEnrolled + 1 > $max){
    $_SESSION['errors'] = array("Unable to Enroll StudentID: $student_id - Course Currently Full.");
    die(header("Location: manageclass.php"));
}

// Add Student To Course
$postData = array("apikey" => APIKEY,
                 "apihash" => APIHASH,
                 "data" => array( "course_id" => $course_id,
                                  "student_id" => $student_id),
                 "action" => "addstudent2course"
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
$_SESSION['successes'] = array("Successfully added Student with ID: $student_id to " . $_SESSION['manage']['name'] . '.');

unset($_SESSION['errors']);
unset($_SESSION['manage']);
die(header("Location: dashboard.php"));
?>