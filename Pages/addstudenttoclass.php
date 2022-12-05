<?php
    session_start();

    require_once("../FormWizard.php");
    require_once("../Template.php");

    $FW = new FormWizard();
    $template = new Template("Add Class");

    if (!isset($_POST, $_POST['id'], $_SESSION['user'], $_SESSION['user']->roles)) {
        $_SESSION['errors'] = array("Session Error");
        die(header("Location: dashboard.php"));
    }

    $course_id = $_POST['id'];

	print $tem->beginHTML();
	
	print '
	<h2>Manage Course</h2>
	<form method="POST" action="submitEnrollClass.php">
        ' . $FW->standardInput("Student ID Number", "student_id", inputType:"number", classes:"") . '
        <input type="hidden" name="id" value="'. $course_id .'/>
		' . $FW->standardSubmit("dashboard.php", "Add Student to Course") . ' />
	</form>';
	
	print $tem->closeHTML();
?>



?>