<?php
    session_start();

    require_once("../FormWizard.php");
    require_once("../Template.php");

    $FW = new FormWizard();
    $tem = new Template("Add Class");

    if (!isset($_POST, $_POST['id'], $_SESSION['user'], json_decode($_SESSION['user'])->user_role)) {
        $_SESSION['errors'] = array("Session Error");
        die(header("Location: dashboard.php"));
    }   
    
	if(json_decode($_SESSION['user'])->user_role != "admin") {
        $_SESSION['errors'] = array("Page forbidden");
        unset($_SESSION["user"]); // this maims everything within the system except the login. Prevents everything essencially
        die(header("Location: index.php"));
    }

    $course_id = $_POST['id'];

    // PRINT PAGE

	print $tem->beginHTML();
	
	print '
	<h2>Manage Course</h2>
	<form method="POST" action="submitEnrollClass.php">
        <label for="student_id">Select a student</input>
        <input name="student_id" type="number" />

        <input type="hidden" name="id" value="'. $course_id .'/>
		' . $FW->standardSubmit("dashboard.php", "Add Student to Course") . ' />
	</form>';
	
	print $tem->closeHTML();
?>