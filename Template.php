<?php
// custom template class and html manager

class Template {

    protected $title;
    protected $head; //stuff in the head tags 
    protected $body; // stuff in the body tag before addition ie nav bars
    protected $tail; // stuff after additions to body, choses it, and adds footers

    protected $headElements = array();
    protected $bodyElements = array();
    protected $tailElements = array();


    function __construct($title) {
        $this->title = $title;
    }

    function addHeadElement($el) {
        $this->headElements[] = $el . "\n";
    }

    function addBodyElement($el) {
        $this->bodyElements[] = $el . "\n";
    }

    function addTailElements($el) {
        $this->tailElements[] = $el . "\n";
    }

    function dumpStart() {
        $output =  "<!doctype html>\n<head lang=\"en\">";
        foreach ($this->headElements as $he) {
            $output .= "\t" . $he . "\n";
        }

        $output .= "</head>\n<body>";
        foreach ($this->bodyElements as $be) {
            $output .= "\t" . $be . "\n";
        }
        return $output;
    }

    function dumpEnd() {
        $output = "";
        foreach ($this->tailElements as $te) {
            $output .= "\t" . $te . "\n";
        }
        $output .= "</body>";
        return $output;
    }

}


?>