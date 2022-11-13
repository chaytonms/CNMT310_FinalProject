<?php

class TableTemplate {

    protected function openContainer(){
        return '<div class="container">';
    }

    protected function closeContainer(){
        return '</div>';
    }

    protected function openTableHead(){
        $head = '<table class="table">';
        $head .= '<thead class="thead-light">';
        return $head;
    }

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
    protected function addStudentClassColumns(){
        $columns = '<tr>';
        $columns .= '<th scope="col">Course Code</th>';
        $columns .= '<th scope="col">Course Number</th>';
        $columns .= '<th scope="col">Course Name</th>';
        $columns .= '<th scope="col">Instructor</th>';
        $columns .= '<th scope="col">Meeting Times</th>';
        $columns .= '<th scope="col">Description</th>';
        $columns .= '</tr>';
        return $columns;
    }

    protected function openTableBody(){
        return '<tbody>';
    }

    protected function closeTable(){
        return '</tbody></table>';
    }

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
            $row .= '<button name="Select" value="' . $class->coursecode . '">Select</button>';
            $row .= '</form></td>';
        }
        $row .= '</tr>';
        return $row;
    }

    // Creates Student Class Rows for Student Dashboard
    protected function addStudentClassRows($class){
        $row = '<tr>';
        $row .= '<td class="col-1">' . $class['coursecode'] . '</td>';
        $row .= '<td class="col-1">' . $class['coursenum'] . '</td>';
        $row .= '<td class="col-2">' . $class['coursename'] . '</td>';
        $row .= '<td class="col-1">' . $class['courseinstr'] . '</td>';
        $row .= '<td class="col-1">' . $class['meetingtimes'] . '</td>';
        $row .= '<td class="col-2">' . $class['coursedesc'] . '</td>';
        $row .= '</tr>';
        return $row;
    }

    public function generateClassSearchResults($searchResults, $addSelectButton, $enrolledClasses){
        $classCodes = array();
        foreach($enrolledClasses as $class){
            $classCodes[] = $class['coursecode'];
        }

        $display = $this->openContainer();

        foreach($searchResults as $result){
            $display .= $this->openTableHead();
            $display .= $this->addClassSearchColumns($addSelectButton);
            $display .= $this->closeTableHead();
            $display .= $this->openTableBody();

            // Checks if student is already enrolled in course - if so, then it won't display Select Btn
            if(in_array($result->coursecode, $classCodes)){
                $display .= $this->addClassSearchRows($result, $addSelectButton, true);
            } else {
                $display .= $this->addClassSearchRows($result, $addSelectButton);
            }
            $display .= $this->closeTable();
        }
        $display .= $this->closeContainer();
        return $display;
    }
}
?>