<?php
// Class - CardTable
// Used to display Student and Class records

class CardTable {
    protected function openTableHead(){
        // div class="container-fluid col-md-10"
        return "<div class=\"container w-50\"><table class=\"table table-bordered table-fixed\">\n<thead class=\"table-dark\">\n"; 
    }

    protected function closeTableHead(){
        return "</thead>";
    }

    protected function openTableBody(){
        return "<tbody>\n";
    }

    protected function closeTableBody(){
        return "</tbody>";
    }

    protected function closeTable(){
        return "</table></div>";
    }

    // Adds Column Names to Table
    protected function addClassColumnNames($addSelectRow){
        $row = "<tr class=\"row\">\n";
        $row = $row . "<th class=\"col-sm-1\">Class ID</th>\n";
        $row = $row . "<th class=\"col-sm-2\">Class Name</th>\n";
        $row = $row . "<th class=\"col-sm-2\">Class Code</th>\n";
        $row = $row . "<th class=\"col-sm-2\">Class Number</th>\n";
        $row = $row . "<th class=\"col-sm-2\">Instructor</th>\n";
        $row = $row . "<th class=\"col-sm-2\">Meeting Time</th>\n";

        if($addSelectRow){
            $row = $row . "<th class=\"col-sm-1\"></th>\n";
        }

        $row = $row . "</tr>\n";
        return $row;
    }

    protected function addRowsForClass($classes, $addSelectButton){
        $row = "<tr class=\"row\">\n";
        $row = $row . "<td class=\"col-sm-1\">" . $classes['classId'] . "</td>\n";
        $row = $row . "<td class=\"col-sm-2\">" . $classes['className'] . "</td>\n";
        $row = $row . "<td class=\"col-sm-2\">" . $classes['classCode'] . "</td>\n";
        $row = $row . "<td class=\"col-sm-2\">" . $classes['classNum'] . "</td>\n";
        $row = $row . "<td class=\"col-sm-2\">" . $classes['instructor'] . "</td>\n";
        $row = $row . "<td class=\"col-sm-2\">" . $classes['meetingTime'] . "</td>\n";

        if($addSelectButton){
            // <a href=\"#\" class=\"btn btn-primary\">Select</a>
            $row = $row . "<td class=\"col-sm-1\"><input type=\"submit\" value=\"Select\"></td>\n";
        }

        $row = $row . "</tr>\n";
        return $row;
    }

    // Takes array of classes as input and returns CardTables as HTML and CSS
    public function generateClassSearchRecords($classSearch, $addSelectButton){
        $cardTable = "";
        foreach($classSearch as $searchResult){
            $cardTable = $cardTable. $this->openTableHead();
            $cardTable = $cardTable . $this->addClassColumnNames($addSelectButton);
            $cardTable = $cardTable . $this->closeTableHead();
            $cardTable = $cardTable . $this->openTableBody();
            $cardTable = $cardTable. $this->addRowsForClass($searchResult, $addSelectButton);
            $cardTable = $cardTable . $this->closeTableBody();
            $cardTable = $cardTable . $this->closeTable();
        }
        return $cardTable;
    }
}
?>