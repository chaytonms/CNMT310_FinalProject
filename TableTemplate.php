<?php

class TableTemplate {

    // Opening Div Element
    protected function openContainer(){
        return '<div class="container">';
    }

    // Closing Div Element
    protected function closeContainer(){
        return '</div>';
    }

    // Opens table and table head
    protected function openTableHead(){
        $head = '<table class="table">';
        $head .= '<thead class="thead-light">';
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

        if($addSelect){
            $columns .= '<th scope="col">Select</th>';
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
        $row .= '<td class="col-1">' . $class->coursecode . '</td>';
        $row .= '<td class="col-1">' . $class->coursenum . '</td>';
        $row .= '<td class="col-2">' . $class->coursename . '</td>';
        $row .= '<td class="col-2">' . $class->courseinstr . '</td>';
        $row .= '<td class="col-2">' . $class->meetingtimes . '</td>';

        if($alreadyEnrolled && $addSelectButton){
            $row .= '<td class="col-1">Already Enrolled</td>';
        } else if($addSelectButton){
            $row .= '<td class="col-1"><form action="classconfirm.php" method="post">';
            $row .= '<button name="Select" value="' . $class->id . '">Select</button>';
            $row .= '</form></td>';
        }
        $row .= '</tr>';
        return $row;
    }

    // Creates Student Class Rows for Student Dashboard
    protected function addStudentClassRows($class, $addSelect){

        // Ensures all 9 Columns available are being used
        if($addSelect){
            $columnSize = array("1", "1", "2", "1", "1", "2", "1");
        } else {
            $columnSize = array("1", "1", "2", "1", "1", "3",);
        }

        $row = '<tr>';
        $row .= '<td class="col-' . $columnSize[0] . '">' . $class->coursecode . '</td>';
        $row .= '<td class="col-' . $columnSize[1] . '">' . $class->coursenum . '</td>';
        $row .= '<td class="col-' . $columnSize[2] . '">' . $class->coursename . '</td>';
        $row .= '<td class="col-' . $columnSize[3] . '">' . $class->courseinstr . '</td>';
        $row .= '<td class="col-' . $columnSize[4] . '">' . $class->meetingtimes . '</td>';
        $row .= '<td class="col-' . $columnSize[5] . '">' . $class->coursedesc . '</td>';
        if($addSelect){
        $row .= '<td class="col-' . $columnSize[6] . '"><input type="checkbox" name="code[]" value="' . $class->id . '"></td>';
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
        $columns .= '<th scope="col">Edit</th>';
        $columns .= '<th scope="col">Delete</th>';
        $columns .= '</tr>';
        return $columns;
    }

    protected function addAdminClassRows($class){
        $row = '<tr>';
        $row .= '<td class="col-1">' . $class->coursecode . '</td>';
        $row .= '<td class="col-1">' . $class->coursenum . '</td>';
        $row .= '<td class="col-1">' . $class->coursename . '</td>';
        $row .= '<td class="col-2">' . $class->courseinstr . '</td>';
        $row .= '<td class="col-2">' . $class->meetingtimes . '</td>';
        $row .= '<td class="col-1"><form action="editclass.php" method="post">';
        $row .= '<button name="Edit" value="' . $class->id . '">Edit</button>';
        $row .= '</form></td>';
        $row .= '<td class="col-1"><form action="deleteClass.php" method="post">';
        $row .= '<button name="Delete" value="' . $class->id . '">Delete</button>';
        $row .= '</form></td>';
        $row .= '</tr>';
        return $row;
    }

    // Function to generate class search result tables (students / guest)
    // $addSelectButton Parameter allows class to be selected (POSTS Class Id)
    public function createStudentClassSearchTables($searchResults, $addSelectButton, $enrolledClasses = array()){
        $classCodes = array();
        foreach($enrolledClasses as $class){
            $classCodes[] = $class->id;
        }

        $display = $this->openContainer();

        foreach($searchResults as $result){
            $display .= $this->openTableHead();
            $display .= $this->addClassSearchColumns($addSelectButton);
            $display .= $this->closeTableHead();
            $display .= $this->openTableBody();

            // Checks if student is already enrolled in course - if so, then it won't display Select Btn
            if(in_array($result->id, $classCodes)){
                $display .= $this->addClassSearchRows($result, $addSelectButton, true);
            } else {
                $display .= $this->addClassSearchRows($result, $addSelectButton);
            }
            $display .= $this->closeTable();
        }
        $display .= $this->closeContainer();
        return $display;
    }

    protected function openFormElement($action, $method){
        return '<form action="'. $action . '" method="' . $method . '">';
    }

    protected function closeFormElement($buttonName){
        return '<input type="submit" value="' . $buttonName . '"></form>';
    }

    // Function to create Student Enrolled classes (student dashboard / class confirmation screen)
    // $addSelect parameter (true / false) - will give table multiselect POST functionality (Posts Class ID)
    public function createStudentDashboardClassTable($studentClasses, $addSelect){
        $display = $this->openContainer();
        if($addSelect){
            $display .= $this->openFormElement("dropclasses.php", "post");
        }
        $display .= $this->openTableHead();
        $display .= $this->addStudentClassColumns($addSelect);
        $display .= $this->closeTableHead();
        $display .= $this->openTableBody();
        foreach($studentClasses as $class){
            $display .= $this->addStudentClassRows($class, $addSelect);
        }
        $display .= $this->closeTable();

        if($addSelect){
            $display .= $this->closeFormElement("Drop Classes");
        }
        $display .= $this-> closeContainer();
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
        $display .= $this-> closeContainer();
        return $display;
    }
}
?>