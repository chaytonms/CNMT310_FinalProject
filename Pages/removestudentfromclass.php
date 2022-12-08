<?php
session_start();
require_once("../ValidationWizard.php");
require_once("../FormWizard.php");
require_once("../SplitPageTemplate.php");
require_once(__DIR__.'/../const.php');
require_once(__DIR__.'/../WebServiceClient.php');

$FW = new FormWizard();
$VW = new ValidationWizard();
$template = new SplitPageTemplate("Remove Student From Class");

function session_error() {
  $_SESSION['errors'] = array("Session Error");
  die(header("Location: index.php"));
}

if (!isset($_SESSION) || !isset($_SESSION['user'])) {
  session_error();
}
$user = json_decode($_SESSION['user']);

if (!isset($user->user_role)) {
  session_error();
}

if ($user->user_role != "admin") {
  $_SESSION['errors'] = array("Page Forbidden");
  die(header("Location: dashboard.php"));
}

if ((!isset($_POST) || !isset($_POST['id'])) && (!isset($_SESSION['manage']) || !isset($_SESSION['manage']['id']) || !isset($_SESSION['manage']['name']))) {
  $_SESSION['errors'] = array("Select a class before trying to remove a student.");
  die(header("Location: dashboard.php"));
}

$classid;
// At this point we know that $_SESSION['manage'] isset, but the logic is to handle the situation where
// a user navigates to manageclass.php and then alters the url to removestudentfromclass.php. In this case only $_SESSION['manage'] would be set.
// If they click the remove student to class button on manageclass.php, $_POST['id'] will bet set and used.
if((!isset($_POST['id']) || empty($_POST['id']))){
  $classid = $_SESSION['manage']['id'];
} else {
  $classid = $_POST['id'];
}

$url = "http://cnmt310.classconvo.com/classreg/";
$client = new WebServiceClient($url);

// Get Students
$postData = array("apikey" => APIKEY,
                  "apihash" => APIHASH,
                  "data" => array( "course_id" => $classid ),
                  "action" => "getstudentlistbycourse"
                );

$client->setPostFields($postData);
$json = (object) json_decode($client->send());

// WHAT SHOULD THE ERROR MESSAGE BE IF THE ERROR IS NOT "No Students found" ??
if($json == null || !isset($json->result) || $json->result != "Success"){
  if((!is_array($json->data) && !isset($json->data->message)) || $json->data->message != "No students found"){
    if ($json->result == "Error") {
      $_SESSION['errors'][] = $json->data->message;
      die(header("Location: manageclass.php"));
    }
  }
  $_SESSION['errors'] = array("There was an error processing your request.");
  die(header("Location: dashboard.php"));
}

$studentsInClass = $json->data;

// PRINT PAGE
print $template->beginHTML();
print $template->openMainNavigation($user->user_role);
print $template->closeMainNavigation();
print $VW->checkSessionErrors($_SESSION);
print $VW->checkSessionSuccesses($_SESSION);
print '<div class="container-fluid d-flex flex-column flex-md-row">';
print '<main class="ps-0 ps-md-5 flex-grow-1">';
print '<div class="container-fluid mt-1 mb-4">';
print '<h3>Remove Student From Course</h3>';
print '<div class="w-75 d-flex justify-content-start">';
print '<form action="submitUnenrollClass.php" method="post">';
print '<ul class="mt-4 mb-4">';
print "<li><h5>" . $_SESSION['manage']['name'] . "</h5></li>";
print '</ul><div class="mt-2">';
print '<select name="student_id" class="form-control" required>';
print "<option disabled selected value>-- Select Student --</option>";
foreach ($studentsInClass as $s) {
  $sid = $s->student_id;
  print "<option value=$sid>$sid</option>";
}
print'</select>';
print '<input type="hidden" name="course_id" value="' . $classid .'">';
print '<button type="submit" class="btn btn-danger button m-2">Confirm Remove</button>';
print '<a href="manageclass.php" class="btn btn-danger button m-2 float-end">Cancel</a>';
print '</div></form></div></div></main></div>';
print $template->closeHTML();
unset($_SESSION['errors']);
?>