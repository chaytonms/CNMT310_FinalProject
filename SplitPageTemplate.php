<?php
    require_once('Template.php');
    class SplitPageTemplate extends Template {
      
        public function openMainNavigation($role){
            $text = "";
            ($role === "guest") ? ($text = "Login") : ($text = "Logout");
            $display = '<header>
            <nav class="navbar navbar-light bg-maroon navbar-expand-sm">
              <a href="dashboard.php" class="navbar-brand mb-0 h1 text-white p-3">Course Registration</a>
              <button 
              type="button" 
              data-bs-toggle="collapse" 
              data-bs-target="#navbarNav" 
              aria-controls="navbarNav" 
              aria-expanded="false" 
              aria-label="Toggle navigation" 
              class="navbar-toggler"><span class="navbar-toggler-icon navbar-dark"></span></button>
              <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                  <li class="nav-item">
                    <a href="dashboard.php" class="nav-link active text-white">Home</a>
                  </li>
                  <li class="nav-item">
                    <a href="../index.php" class="nav-link text-white">' . $text . '</a>
                  </li>
                </ul>';
                return $display;
        }

        public function closeMainNavigation(){
            return '</div></nav></header>';
        }
        
        public function openAdminDashboard($role, $name){
            $display = '<div class="container-fluid d-flex flex-column flex-md-row">
            <nav class="navbar navbar-expand-md navbar-light d-flex flex-md-column">
              <div class="w-100" id="navbarSupportedContent">
                <ul class="navbar-nav w-100 d-flex flex-md-column text-center text-md-end">
                  <li>
                    <p class="h5">' . ucfirst($role) . ": " . $name . '</p>
                  </li>
                  <li>
                    <a href="addClass.php" class="nav-link">Add Class</a>
                  </li>
                  <li>
                    <a href="addstudenttoclass.php" class="nav-link">Add Student To Class</a>
                  </li>
                  <li>
                    <a href="removestudentfromclass.php" class="nav-link">Remove Student From Class</a>
                  </li>
                </ul>
              </div>
            </nav>
          
            <main class="ps-0 ps-md-5 flex-grow-1">
              <div class="container-fluid mt-1 mb-4">
                <form action="adminsearch.php" method="post" class="form-horizontal">
                  <div class="ui-widget input-group w-100">
                  <input type="search" id="class" name="term" class="form-control rounded form-control" placeholder="Search for Classes" aria-label="Search">
                  <button type="submit" class="btn btn-danger button">Search</button>
                  </div>
                </form>
              </div>
              <div class="container-fluid">';
              return $display;
        }

        public function openStudentDashboard($role, $name){
            $display = '<div class="container-fluid d-flex flex-column flex-md-row">
            <nav class="navbar navbar-expand-md navbar-light d-flex flex-md-column">
              <div class="w-100" id="navbarSupportedContent">
                <ul class="navbar-nav w-100 d-flex flex-md-column text-center text-md-end">
                  <li>
                    <p class="h5">' . ucfirst($role) . ": " . $name . '</p>
                  </li>
                </ul>
              </div>
            </nav>
          
            <main class="ps-0 ps-md-5 flex-grow-1">
              <div class="container-fluid mt-1 mb-4">
                <form action="searchresults.php" method="post" class="form-horizontal">
                  <div class="ui-widget input-group w-100">
                  <input type="search" id="class" name="term" class="form-control rounded form-control" placeholder="Search for Classes" aria-label="Search">
                  <button type="submit" class="btn btn-danger button">Search</button>
                  </div>
                </form>
              </div>
              <div class="container-fluid">';
              return $display;
        }

        public function openGuestDashboard($role){
            $display = '<div class="container-fluid d-flex flex-column flex-md-row">
            <nav class="navbar navbar-expand-md navbar-light d-flex flex-md-column">
              <div class="w-100" id="navbarSupportedContent">
                <ul class="navbar-nav w-100 d-flex flex-md-column text-center text-md-end">
                  <li>
                    <p class="h5">' . ucfirst($role) . '</p>
                  </li>
                </ul>
              </div>
            </nav>
          
            <main class="ps-0 ps-md-5 flex-grow-1">
              <div class="container-fluid mt-1 mb-4">
                <form action="searchresults.php" method="post" class="form-horizontal">
                  <div class="ui-widget input-group w-100">
                  <input type="search" id="class" name="term" class="form-control rounded form-control" placeholder="Search for Classes" aria-label="Search">
                  <button type="submit" class="btn btn-danger button">Search</button>
                  </div>
                </form>
              </div>
              <div class="container-fluid">';
              return $display;
        }

        public function closeDashboard(){
            return '</div></main></div>';
        }
    }

?>