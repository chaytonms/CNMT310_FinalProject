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

    protected function openTableBody(){
        return '<tbody>';
    }

    protected function closeTable(){
        return '</tbody></table>';
    }

    protected function addClassSearchRows($class, $addSelectButton, $alreadyEnrolled = false){
        $row = '<tr>';
        $row .= '<td class="col-1">' . $class['classCode'] . '</td>';
        $row .= '<td class="col-1">' . $class['classNum'] . '</td>';
        $row .= '<td class="col-2">' . $class['className'] . '</td>';
        $row .= '<td class="col-2">' . $class['instructor'] . '</td>';
        $row .= '<td class="col-2">' . $class['meetingTime'] . '</td>';

        if($alreadyEnrolled && $addSelectButton){
            $row .= '<td class="col-1">Already Enrolled</td>';
        } else if($addSelectButton){
            $row .= '<td class="col-1"><form action="classconfirm.php" method="post">';
            $row .= '<button name="Select" value="' . $class['classId'] . '">Select</button>';
            $row .= '</form></td>';
        }
        $row .= '</tr>';
        return $row;
    }

    // Input Search Results from WebService, ture or false for select (not a guest view), and the $_SESSION['enrolledClasses'] for Enrolled Classes
    public function generateClassSearchResults($searchResults, $addSelectButton, $enrolledClasses){
        $display = $this->openContainer();

        // Add Logic to check if classID is in Enrolled ID
        foreach($searchResults as $result){
            $display .= $this->openTableHead();
            $display .= $this->addClassSearchColumns($addSelectButton);
            $display .= $this->closeTableHead();
            $display .= $this->openTableBody();

            if(in_array($result['classId'], $enrolledClasses)){
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