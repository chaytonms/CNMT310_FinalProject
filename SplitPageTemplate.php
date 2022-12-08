<?php
require_once('Template.php');
class SplitPageTemplate extends Template {

  // Constructor
  public function __construct($title) {
    parent::__construct($title);
  }

  public function openMainNavigation($role){
    $text = "";
    ($role === "guest") ? ($text = "Login") : ($text = "Logout");
    $display = '<header><nav class="navbar navbar-light bg-maroon navbar-expand-sm">';
    $display .= '<a href="dashboard.php" class="navbar-brand mb-0 h1 text-white p-3">Course Registration</a>';
    $display .= '<button type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" ';
    $display .= 'aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler">';
    $display .= '<span class="navbar-toggler-icon navbar-dark"></span></button>';
    $display .= '<div class="collapse navbar-collapse" id="navbarNav">';
    $display .= '<ul class="navbar-nav">';
    $display .= '<li class="nav-item"><a href="dashboard.php" class="nav-link active text-white">Home</a></li>';
    $display .= '<li class="nav-item"><a href="../index.php" class="nav-link text-white">' . $text . '</a></li></ul>';
    return $display;
  }

  public function closeMainNavigation(){
    return '</div></nav></header>';
  }

  public function openAdminDashboard($role, $name){
    $display = '<div class="container-fluid d-flex flex-column flex-md-row">';
    $display .= '<nav class="navbar navbar-expand-md navbar-light d-flex flex-md-column">';
    $display .= '<div class="w-100" id="navbarSupportedContent">';
    $display .= '<ul class="navbar-nav w-100 d-flex flex-md-column text-center text-md-end">';
    $display .= '<li><p class="h5">' . ucfirst($role) . ": " . $name . '</p></li>';
    $display .= '<li><a href="addClass.php" class="nav-link">Add Class</a></li></ul></div></nav>';
    $display .= '<main class="ps-0 ps-md-5 flex-grow-1"><div class="container-fluid mt-1 mb-4">';
    $display .= '<form action="searchresults.php" method="post" class="form-horizontal">';
    $display .= '<div class="ui-widget input-group w-100">';
    $display .= '<input type="search" id="class" name="term" class="form-control rounded form-control" placeholder="Search for Classes" aria-label="Search">';
    $display .= '<input type="hidden" id="search" name="searchterm">';
    $display .= '<button type="submit" class="btn btn-danger button">Search</button>';
    $display .= '</div></form></div><div class="container-fluid">';
    return $display;
  }

  public function openManageClass($role, $name){
    $display = '<div class="container-fluid d-flex flex-column flex-md-row">';
    $display .= '<nav class="navbar navbar-expand-md navbar-light d-flex flex-md-column">';
    $display .= '<div class="w-100" id="navbarSupportedContent">';
    $display .= '<ul class="navbar-nav w-100 d-flex flex-md-column text-center text-md-end">';
    $display .= '<li><p class="h5">' . ucfirst($role) . ": " . $name . '</p></li></ul>';
    $display .= '</div></nav><main class="ps-0 ps-md-5 flex-grow-1"><div class="container">';
    return $display;
  }

  public function openStudentDashboard($role, $name){
    $display = '<div class="container-fluid d-flex flex-column flex-md-row">';
    $display .= '<nav class="navbar navbar-expand-md navbar-light d-flex flex-md-column">';
    $display .= '<div class="w-100" id="navbarSupportedContent">';
    $display .= '<ul class="navbar-nav w-100 d-flex flex-md-column text-center text-md-end">';
    $display .= '<li><p class="h5">' . ucfirst($role) . ": " . $name . '</p></li></ul></div></nav>';
    $display .= '<main class="ps-0 ps-md-5 flex-grow-1">';
    $display .= '<div class="container-fluid mt-1 mb-4">';
    $display .= '<form action="searchresults.php" method="post" class="form-horizontal">';
    $display .= '<div class="ui-widget input-group w-100">';
    $display .= '<input type="search" id="class" name="term" class="form-control rounded form-control" placeholder="Search for Classes" aria-label="Search">';
    $display .= '<input type="hidden" id="search" name="searchterm">';
    $display .= '<button type="submit" class="btn btn-danger button">Search</button>';
    $display .= '</div></form></div><div class="container-fluid">';
    return $display;
  }

  public function openGuestDashboard($role){
    $display = '<div class="container-fluid d-flex flex-column flex-md-row">';
    $display .= '<nav class="navbar navbar-expand-md navbar-light d-flex flex-md-column">';
    $display .= '<div class="w-100" id="navbarSupportedContent">';
    $display .= '<ul class="navbar-nav w-100 d-flex flex-md-column text-center text-md-end">';
    $display .= '<li><p class="h5">' . ucfirst($role) . '</p></li></ul></div></nav>';
    $display .= '<main class="ps-0 ps-md-5 flex-grow-1">';
    $display .= '<div class="container-fluid mt-1 mb-4">';
    $display .= '<form action="searchresults.php" method="post" class="form-horizontal">';
    $display .= '<div class="ui-widget input-group w-100">';
    $display .= '<input type="search" id="class" name="term" class="form-control rounded form-control" placeholder="Search for Classes" aria-label="Search">';
    $display .= '<input type="hidden" id="search" name="searchterm">';
    $display .= '<button type="submit" class="btn btn-danger button">Search</button>';
    $display .= '</div></form></div><div class="container-fluid">';
    return $display;
  }

  public function closeDashboard(){
    return '</div></main></div>';
  }
}
?>