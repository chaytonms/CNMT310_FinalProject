<?php
session_start();
require_once("../ValidationWizard.php");
require_once("../FormWizard.php");
require_once("../SplitPageTemplate.php");

$FW = new FormWizard();
$VW = new ValidationWizard();
$template = new SplitPageTemplate("Add Student To Class");

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

if ((!isset($_POST) || !isset($_POST['id'])) && (!isset($_SESSION['manage']) 
|| !isset($_SESSION['manage']['id']) || !isset($_SESSION['manage']['name']) || !isset($_SESSION['manage']['max']))) {
  $_SESSION['errors'] = array("Select a class before trying to add a student.");
  die(header("Location: dashboard.php"));
}

$classid;
// At this point we know that $_SESSION['manage'] isset, but the logic is to handle the situation where
// a user navigates to manageclass.php and then alters the url to addstudenttoclass.php. In this case only $_SESSION['manage'] would be set.
// If they click the add student to class button on manageclass.php, $_POST['id'] will bet set and used.
if((!isset($_POST['id']) || empty($_POST['id']))){
  $classid = $_SESSION['manage']['id'];
} else {
  $classid = $_POST['id'];
}

// PRINT PAGE
print $template->beginHTML();
print $template->openMainNavigation($user->user_role);
print $template->closeMainNavigation();
print $VW->checkSessionErrors($_SESSION);
print $VW->checkSessionSuccesses($_SESSION);
print '<div class="container-fluid d-flex flex-column flex-md-row">';
print '<main class="ps-0 ps-md-5 flex-grow-1">';
print '<div class="container-fluid mt-1 mb-4">';
print '<h3>Add Student To Course</h3>';
print '<div class="w-75 d-flex justify-content-start">';
print '<form action="submitEnrollClass.php" method="post">';
print '<ul class="mt-4 mb-4">';
print "<li><h5>" . $_SESSION['manage']['name'] . "</h5></li>";
print '</ul><div class="mt-2">';
print '<input type="hidden" name="max" value="' . $_SESSION['manage']['max'] . '">';
print '<input name="student_id" type="number" min="0" max="9999999" class="form-control" placeholder="Enter Student ID" required>';
print '<button type="submit" name="course_id" class="btn btn-danger button m-2" value="' . $classid . '">Confirm Add</button>';
print '<a href="manageclass.php" class="btn btn-danger button m-2">Cancel</a>';
print '</div></form></div></div></main></div>';
print $template->closeHTML();
unset($_SESSION['errors']);
?>