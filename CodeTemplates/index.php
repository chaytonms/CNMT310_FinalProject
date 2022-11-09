<?php
session_start();
require_once("Template.php");
require_once("ClassData.php");
require_once("TableTemplate.php");

$classData = new ClassData();
$_SESSION['classData'] = $classData->getClassList();

// Print HTML
$page = new Template("Step 2 - Select A Class");
$page->addHeadElement('<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">');
$page->addHeadElement('<link rel="stylesheet" href="extensions/fixed-columns/bootstrap-table-fixed-columns.css">');
$page->addHeadElement('<meta name="viewport" content="width=device-width, initial-scale=1.0">');
$page->finalizeTopSection();
$page->addBottomElement('<script src="extensions/fixed-columns/bootstrap-table-fixed-columns.js"></script>');
$page->addBottomElement('<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>');
$page->finalizeBottomSection();

print $page->getTopSection();
// Logic to add cureently enrolled
$_SESSION['currentlyEnrolled'][] = "90125";

print "\t<h1>Select a Class</h1>\n";
$table = new TableTemplate();
print '<div class="container"><div class="row"><div class="col-3"></div><div class="col-9">';
print $table->generateClassSearchResults($_SESSION['classData'], true, $_SESSION['currentlyEnrolled']);
print '</div></div></div>';
print $page->getBottomSection();
?>