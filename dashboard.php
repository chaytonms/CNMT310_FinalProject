<?php
session_start();
require_once("Template.php");

if (!isset($_SESSION) || !isset($_SESSION['role'])) {
    $_SESSION['errors'] = array("Session Error");
    die(header("Location: index.php"));
}
$role = $_SESSION['role'];

$template = new Template("Auth");

$template->addBodyElement("<p>Hello!</p>");

print $template->beginHTML();

print "Detected role: $role";

print $template->closeHTML();
?>