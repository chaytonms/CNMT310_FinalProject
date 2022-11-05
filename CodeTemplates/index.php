<?php
session_start();
require_once("Template.php");
require_once("ClassData.php");
require_once("CardTable.php");

$classData = new ClassData();
$_SESSION['classData'] = $classData->getClassList();

// Print HTML
$page = new Template("Step 2 - Select A Class");
$page->addHeadElement('<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">');
$page->addHeadElement('<meta name="viewport" content="width=device-width, initial-scale=1.0">');
$page->finalizeTopSection();
$page->addBottomElement('<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>');
$page->finalizeBottomSection();

print $page->getTopSection();
print "\t<h1>Select a Class</h1>\n";

$cardTable = new CardTable();

print $cardTable->generateClassSearchRecords($_SESSION['classData'], true);

print $page->getBottomSection();
?>