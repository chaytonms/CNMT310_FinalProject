<?php
/*
Page Description: Displays search results when a user searchs anything using the search bar. If no search term is entered or a user navigates to this page directly via URL,
it will display all the available courses in the system.
*/

session_start();
require_once(__DIR__.'/../SplitPageTemplate.php');
require_once(__DIR__.'/../TableTemplate.php');
require_once(__DIR__.'/../WebServiceClient.php');
require_once(__DIR__.'/../const.php');
require_once(__DIR__.'/../ValidationWizard.php');

$template = new SplitPageTemplate("Course Search");
$VW = new ValidationWizard();
$table = new TableTemplate();

// Validation
if (!isset($_SESSION) || !isset($_SESSION['user'])) {
    session_error();
}
$user = json_decode($_SESSION['user']);

if (!isset($user->user_role)) {
    session_error();
}
$role = $user->user_role;

$name;
if($user->user_role != "guest"){
    if (!isset($user->name)) {
        session_error();
    }
    $name = $user->name; 
}

// Will be used to decide whether to display all courses, or courses based on the term
// In situations where the user does not enter a search term, all courses will be returned
$query;

// Will be used to decide what $_POST key to query on
// If they select a course from the search dropdown - used $_POST['searchterm']
// If they don't select from dropdown (e.g. type only 2 letters and hit search/enter), use $_POST['term']
$term;

if((isset($_POST['term']) && !empty($_POST['term'])) || (isset($_POST['searchterm']) && !empty($_POST['searchterm']))){
    $query = true;
    if(isset($_POST['searchterm']) && !empty($_POST['searchterm']) && trim($_POST['searchterm']) !== ""){
        $term = $_POST['searchterm'];
    } else if(isset($_POST['term']) && !empty($_POST['term']) && trim($_POST['term']) !== ""){
        $term = $_POST['term'];
    } else {
        // Avoiding search on only white space
        $query = false;
        $term = null;
    }
} else {
    $query = false;
    $term = null;
}

$url = "http://cnmt310.classconvo.com/classreg/";
$client = new WebServiceClient($url);
$postData;
$result;

$enrolledCourses;
// Grab and set $enrolledCourses if user is student
// Needed do display correct TableTemplate Output (if student is already enrolled in a course it will display "Already Enrolled")
// Instead of a select button
if($role == "student"){
    if (!isset($user->id)) {
        session_error();
    }
    $postData = array("apikey" => APIKEY,
                  "apihash" => APIHASH,
                  "data" => array("student_id" => $user->id),
                  "action" => "getstudentcourses"
                );
    $client->setPostFields($postData);
    $json = (object) json_decode($client->send());
    if($json == null || !isset($json->result) || $json->result != "Success"){
        $_SESSION['errors'] = array("An error occurred while attempting to process your request.");
        die(header("Location: dashboard.php"));
    }
    $enrolledCourses = $json->data;
}

// Either query courses with term or return all courses
if($query){
    $postData = array("apikey" => APIKEY,
                  "apihash" => APIHASH,
                  "data" => array( "term" => $term ),
                  "action" => "searchcourses"
                );
    $client->setPostFields($postData);
    $json = (object) json_decode($client->send());

    if($json == null || !isset($json->result) || $json->result != "Success"){
        $_SESSION['errors'] = array("An error occurred while attempting to process your request.");
        die(header("Location: dashboard.php"));
    }
    $result = $json->data;
} else {
    $postData = array("apikey" => APIKEY,
                  "apihash" => APIHASH,
                  "data" => array(),
                  "action" => "listcourses"
                );
    $client->setPostFields($postData);
    $json = (object) json_decode($client->send());
    if($json == null || !isset($json->result) || $json->result != "Success"){
        $_SESSION['errors'] = array("An error occurred while attempting to process your request.");
        die(header("Location: dashboard.php"));
    }
    $result = $json->data;
}

// PRINT PAGE
print $template->beginHTML();
print $template->openMainNavigation($user->user_role);
print $template->closeMainNavigation();
print '<div class="container-fluid d-flex flex-column flex-md-row">';
print '<main class="ps-0 ps-md-5 flex-grow-1">';
print '<div class="container-fluid mt-1 mb-4">';
print '<h3>Course Search</h3>';
print '<form action="searchresults.php" method="post" class="form-horizontal">';
print '<div class="ui-widget input-group w-100">';
print '<input type="search" id="class" name="term" class="form-control rounded form-control" placeholder="Search for Classes" aria-label="Search">';
print '<input type="hidden" id="search" name="searchterm">';
print '<button type="submit" class="btn btn-danger button">Search</button>';
print '</div></form>';
print $VW->checkSessionErrors($_SESSION);
print $VW->checkSessionSuccesses($_SESSION);
if($term != null){
    print "<p>Showing Search Results for \"$term\""; 
}
if(count($result) > 0){
    if($role == "admin"){
        print $table->createAdminDashboardClassTable($result);
    } else if($role == "student"){
        print $table->createStudentClassSearchTables($result, true, $enrolledCourses);
    } else {
        print $table->createStudentClassSearchTables($result, false);
    }
} else {
    print '<h4>No courses matched your search.';
}
print '</div></main></div>';
print $template->closeHTML();
unset($_SESSION['errors']);
unset($_SESSION['manage']);
?>