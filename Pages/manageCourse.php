<?php 
	session_start();

	require_once("../Template.php");
	$tem = new Template("Manage Course");
	
	if (!isset($_POST, $_POST['course_id'], $_SESSION['user'], $_SESSION['user']->role)) {
		$_SESSION['errors'] = array("Session Error");
		die(header("Location: dashboard.php"));
	}
	
	
	$course_id = $_POST['course_id'];

	print $tem->beginHTML();
	
	print '
	<h2>Manage Course</h2>
	<form method="POST" action="">
		<input type="hidden" name="course_id" value=' . $course_id . ' />
		<input type="submit" name="submitform" value="Add Student to Course" />
	</form>';
	print '
	<form method="POST" action="">
		<input type="hidden" name="course_id" value=' . $course_id . ' />
		<input type="submit" name="submitform" value="Remove Student to Course" />
	</form>';
	print '
	<form method="POST" action="">
		<input type="hidden" name="course_id" value=' . $course_id . ' />
		<input type="submit" name="submitform" value="Delete Course" />
	</form>';


	print $tem->closeHTML();
?>