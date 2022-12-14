<?php

class TableTemplate {

    // Opening Div Element
    protected function openContainer(){
        return '<div class="table-responsive">';
    }

    // Closing Div Element
    protected function closeContainer(){
        return '</div>';
    }

    // Opens table and table head
    protected function openTableHead(){
        $head = '<table class="table table-striped">';
        $head .= '<thead class="tableMaroon">';
        return $head;
    }

    // Closes table head
    protected function closeTableHead(){
        return '</thead>';
    }

    // Creates Search Result tables for Student and Guest
    protected function addClassSearchColumns($addSelect){
        $columns = '<tr>';
        $columns .= '<th scope="col">Course Code</th>';
        $columns .= '<th scope="col">Course Number</th>';
        $columns .= '<th scope="col">Course Name</th>';
        $columns .= '<th scope="col">Instructor</th>';
        $columns .= '<th scope="col">Meeting Times</th>';
        $columns .= '<th scope="col">Credits</th>';
        $columns .= '<th scope="col">Description</th>';
        $columns .= '<th scope="col">Max Enrollment</th>';

        if($addSelect){
            $columns .= '<th scope="col">Enroll</th>';
        }
        $columns .= '</tr>';
        return $columns;
    }

    // Creates Student Class Columns for Student Dashboard
    protected function addStudentClassColumns($addSelect){
        $columns = '<tr>';
        $columns .= '<th scope="col">Course Code</th>';
        $columns .= '<th scope="col">Course Number</th>';
        $columns .= '<th scope="col">Course Name</th>';
        $columns .= '<th scope="col">Instructor</th>';
        $columns .= '<th scope="col">Meeting Times</th>';
        $columns .= '<th scope="col">Credits</th>';
        $columns .= '<th scope="col">Description</th>';
        if($addSelect){
            $columns .= '<th scope="col">Select</th>';
        }
        $columns .= '</tr>';
        return $columns;
    }

    // Opens table body
    protected function openTableBody(){
        return '<tbody>';
    }

    // Closes table body and Table
    protected function closeTable(){
        return '</tbody></table>';
    }

    // Creates row to display on Class Search Results (Student / Guest)
    protected function addClassSearchRows($class, $addSelectButton, $alreadyEnrolled = false){
        $row = '<tr>';
        $row .= '<td class="col-md">' . $class->coursecode . '</td>';
        $row .= '<td class="col-md">' . $class->coursenum . '</td>';
        $row .= '<td class="col-md">' . $class->coursename . '</td>';
        $row .= '<td class="col-md">' . $class->courseinstr . '</td>';
        $row .= '<td class="col-md">' . $class->meetingtimes . '</td>';
        $row .= '<td class="col-md">' . $class->coursecredits . '</td>';
        $row .= '<td class="col-md">' . $class->coursedesc . '</td>';
        $row .= '<td class="col-md">' . $class->maxenroll . '</td>';

        if($alreadyEnrolled && $addSelectButton){
            $row .= '<td class="col-md">Already Enrolled</td>';
        } else if($addSelectButton){
            $row .= '<td class="col-md"><form action="classconfirm.php" method="post">';
            $row .= '<button class="btn btn-danger button" name="course_id" value="' . $class->id . '">Enroll</button>';
            $row .= '</form></td>';
        }
        $row .= '</tr>';
        return $row;
    }

    // Creates Student Class Rows for Student Dashboard
    protected function addStudentClassRows($class, $addSelect){
        $row = '<tr>';
        $row .= '<td class="col-md">' . $class->coursecode . '</td>';
        $row .= '<td class="col-md">' . $class->coursenum . '</td>';
        $row .= '<td class="col-md">' . $class->coursename . '</td>';
        $row .= '<td class="col-md">' . $class->courseinstr . '</td>';
        $row .= '<td class="col-md">' . $class->meetingtimes . '</td>';
        $row .= '<td class="col-md">' . $class->coursecredits . '</td>';
        $row .= '<td class="col-md">' . $class->coursedesc . '</td>';
        if($addSelect){
        $row .= '<td class="col-md"><input type="checkbox" name="code[]" value="' . $class->course_id . '"></td>';
        }
        $row .= '</tr>';
        return $row;
    }

    protected function addAdminClassColumns(){
        $columns = '<tr>';
        $columns .= '<th scope="col">Course Code</th>';
        $columns .= '<th scope="col">Course Number</th>';
        $columns .= '<th scope="col">Course Name</th>';
        $columns .= '<th scope="col">Instructor</th>';
        $columns .= '<th scope="col">Meeting Times</th>';
        $columns .= '<th scope="col">Manage Course</th>';
        $columns .= '</tr>';
        return $columns;
    }

    protected function addAdminClassRows($class){
        $row = '<tr>';
        $row .= '<td class="col-md">' . $class->coursecode . '</td>';
        $row .= '<td class="col-md">' . $class->coursenum . '</td>';
        $row .= '<td class="col-md">' . $class->coursename . '</td>';
        $row .= '<td class="col-md">' . $class->courseinstr . '</td>';
        $row .= '<td class="col-md">' . $class->meetingtimes . '</td>';
        $row .= '<td class="col-md"><form action="manageclass.php" method="post">';
        $row .= '<button class="btn btn-danger button" name="id" value="' . $class->id . '">Manage Course</button>';
        $row .= '</form></td>';
        $row .= '</tr>';
        return $row;
    }

    // Function to generate class search result tables (students / guest)
    // $addSelectButton Parameter allows class to be selected (POSTS Class Id)
    public function createStudentClassSearchTables($searchResults, $addSelectButton, $enrolledClasses = array()){
        $classCodes = array();
        foreach($enrolledClasses as $class){
            $classCodes[] = $class->course_id;
        }
        $display = $this->openContainer();
        $display .= $this->openTableHead();
        $display .= $this->addClassSearchColumns($addSelectButton);
        $display .= $this->closeTableHead();
        $display .= $this->openTableBody();
        foreach($searchResults as $result){
            // Checks if student is already enrolled in course - if so, then it won't display Select Btn
            if(in_array($result->id, $classCodes)){
                $display .= $this->addClassSearchRows($result, $addSelectButton, true);
            } else {
                $display .= $this->addClassSearchRows($result, $addSelectButton);
            }
        }
        $display .= $this->closeTable();
        $display .= $this->closeContainer();
        return $display;
    }

    protected function openFormElement($action, $method){
        return '<form action="'. $action . '" method="' . $method . '">';
    }

    protected function closeFormElement($buttonName){
        return '<button class="btn btn-danger button">' . $buttonName . '</button></form>';
    }

    // Function to create Student Enrolled classes (student dashboard / class confirmation screen)
    // $addSelect parameter (true / false) - will give table multiselect POST functionality (Posts Class ID)
    public function createStudentDashboardClassTable($studentClasses, $addSelect){
        $display = "";
        if($addSelect){
            $display .= $this->openFormElement("dropclasses.php", "post");
        }
        $display .= $this->openContainer();
        $display .= $this->openTableHead();
        $display .= $this->addStudentClassColumns($addSelect);
        $display .= $this->closeTableHead();
        $display .= $this->openTableBody();
        foreach($studentClasses as $class){
            $display .= $this->addStudentClassRows($class, $addSelect);
        }
        $display .= $this->closeTable();
        $display .= $this->closeContainer();
        if($addSelect){
            $display .= $this->closeFormElement("Drop Classes");
        }
        return $display;
    }

    // Function to Create Admin Current Classes Table
    public function createAdminDashboardClassTable($classes){
        $display = $this->openContainer();
        $display .= $this->openTableHead();
        $display .= $this->addAdminClassColumns();
        $display .= $this->closeTableHead();
        $display .= $this->openTableBody();
        foreach($classes as $class){
            $display .= $this->addAdminClassRows($class);
        }
        $display .= $this->closeTable();
        $display .= $this->closeContainer();
        return $display;
    }
}
?>