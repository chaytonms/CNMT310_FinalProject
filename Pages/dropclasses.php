<?php
session_start();
require_once("../FormWizard.php");
require_once("../ValidationWizard.php");
require_once("../Template.php");
require_once(__DIR__.'/../WebServiceClient.php');
require_once(__DIR__.'/../const.php');
require_once(__DIR__.'/../SplitPageTemplate.php');
require_once(__DIR__.'/../const.php');

$FW = new FormWizard();
$VW = new ValidationWizard();
$template = new SplitPageTemplate("Drop Classes Confirmation");

// Validation Checks user first, so we can determine the correct error message to display
// If Guest - Page Forbidden
// If Admin - Drop Classes is a Student Function
// If Student (with no post) - Select classes to drop using the checkboxes

if (!isset($_SESSION) || !isset($_SESSION['user'])) {
    session_error();
}
$user = json_decode($_SESSION['user']);

if (!isset($user->user_role)) {
    session_error();
}

if ($user->user_role != "student" && $user->user_role != "admin") {
    forbidden_error();
}

if($user->user_role != "student"){
    $_SESSION['errors'] = array("Drop Classes is a Student Function.");
    die(header("Location: dashboard.php"));
}

if (!isset($_POST) || !isset($_POST['code'])) {
    $_SESSION['errors'] = array("Select classes to drop using the checkboxes.");
    die(header("Location: dashboard.php"));
}

if (!is_array($_POST['code']) || count($_POST['code']) <= 0) {
    $_SESSION['errors'] = array("Select classes to drop using the checkboxes.");
    die(header("Location: dashboard.php"));
}

// Calling Web Service List Courses to get class information to Display Drop List to User
$url = "http://cnmt310.classconvo.com/classreg/";
$client = new WebServiceClient($url);

$postData = array("apikey" => APIKEY,
          "apihash" => APIHASH,
          "data" => array(),
          "action" => "listcourses"
          );

$client->setPostFields($postData);
$json = (object) json_decode($client->send());

if ($json == null || !isset($json->result) || $json->result != "Success") {
    $_SESSION['errors'] = array("Error with fetching class.");
    die(header("Location: dashboard.php"));
} 

$classArray = $_POST['code'];
$classes = $json->data;

// Will be used on submitDropClasses.php to Remove Student From Classes
$_SESSION['classesToDrop'] = $_POST['code'];

// Print Screen HTML
print $template->beginHTML();
print $template->openMainNavigation($user->user_role);
print $template->closeMainNavigation();
$closer = (count($classArray) > 1) ? ("s") : ("");

print '<div class="container-fluid d-flex flex-column flex-md-row">';
print '<main class="ps-0 ps-md-5 flex-grow-1">';
print '<div class="container-fluid mt-1 mb-4">';
print '<h3>Are you sure you want to drop the following course' . $closer . '?</h3>';
print '<div><form action="submitDropClasses.php" method="post"><ol>';
foreach($classes as $c){
    if (in_array("".$c->id, $classArray)) {
        print "<li><p>" . $c->coursecode . " " . $c->coursenum . ": " . $c->coursename . "</p></li>";
    }
}
print '</ol><div class="mt-2">';
print '<button type="submit" name="submitform" class="btn btn-danger button m-1" value="confirmed">Confirm Drop</button>';
print '<a href="dashboard.php" class="btn btn-danger button m-1">Cancel</a>';
print '</div></form></div></div></main></div>';
print $template->closeHTML();
?>