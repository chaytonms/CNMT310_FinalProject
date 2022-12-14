<?php
session_start();
require_once("../ValidationWizard.php");
require_once("../SplitPageTemplate.php");

// Ensures an admin can't naviagte to manageclass.php, then to this page, and then back to manageclass.php
unset($_SESSION['manage']);

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

// ### data fields: ###
//    "coursename": "Principles of Computing", 
//     "coursecode": "CNMT", 
//     "coursenum": "100", 
//     "coursecredits": "3", 
//     "coursedesc": "Exploring the principles of computing", 
//    "courseinstr": "Simkins", 
//     "meetingtimes": "MW 11:00a-12:15p", 
//     "maxenroll": "24"  

$VW = new ValidationWizard(); 
$template = new SplitPageTemplate('Add Course');

// PRINT HTML
print $template->beginHTML();
print $template->openMainNavigation($user->user_role);
print $template->closeMainNavigation();
print $VW->checkSessionErrors($_SESSION);
print $VW->checkSessionSuccesses($_SESSION);
print '<div class="container-fluid d-flex flex-column flex-md-row">';
print '<main class="ps-0 ps-md-5 flex-grow-1">';
print '<div class="container-fluid mt-1 mb-4">';
print '<h3>Add Course</h3>';
print '<div class="w-75 d-flex justify-content-start">';
print '<form action="submitAddClass.php" method="POST">'; 

print '<label for="coursename">Course Name:</label>';
print '<input id="coursename" name="coursename" type="text" class="form-control" placeholder="e.g. Production Programming" required/>';

print '<label for="coursecode">Course Code:</label>';
print '<input id="coursecode" name="coursecode" type="text" class="form-control" placeholder="e.g. CNMT" required/>';

print '<label for="coursenum">Course Number:</label>';
print '<input id="coursenum" name="coursenum" type="number" class="form-control" placeholder="e.g. 310" min="0" max="9999999" required/>';

print '<label for="coursecredits">Course Credits:</label>';
print '<input id="coursecredits" name="coursecredits" type="number" class="form-control" placeholder="e.g. 4" min="0" max="9999999" required/>';

print '<label for="coursedesc">Course Description:</label>';
print '<textarea id="coursedesc" rows="5" cols="40" name="coursedesc" type="text" class="form-control" placeholder="e.g. Learning things about the Internet" required></textarea>';

print '<label for="courseinstr">Course Instructor:</label>';
print '<input id="courseinstr" name="courseinstr" type="text" class="form-control" placeholder="e.g. Suehring" required/>';

print '<label for="meetingtimes">Meeting Times:</label>';
print '<input id="meetingtimes" name="meetingtimes" type="text" class="form-control" placeholder="e.g. MW 11:00a-12:50p" required/>';

print '<label for="maxenroll">Max Enrollment:</label>';
print '<input id="maxenroll" name="maxenroll" type="number" class="form-control" placeholder="e.g. 24" min="0" max="9999999" required/>';
print '<button type="submit" name="submitform" class="btn btn-danger button m-2">Add Course</button>';
print '<a href="dashboard.php" class="btn btn-danger button m-2">Cancel</a>';
print '</form></div>';
print '</div></main></div>';
print $template->closeHTML();
unset($_SESSION['errors']);
?>