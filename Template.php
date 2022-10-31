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


    public function __construct($title) {
        $this->title = $title;
        $this->head = "<!doctype html>\n<head lang=\"en\">"; // insert default header code here
        $this->body = "</head>\n<body>\n"; // insert end of head + start of body here
        $this->tail = ""; // add default end of body here
    }

    /**
     * function addHeadElements ($el)
     * adds element to head, such as a link or script tag
     * 
     */
    public function addHeadElement($el) {
        $this->headElements[] = $el . "\n";
    }

    /**
     * function addBodyElements ($el)
     * adds element to body, before any specialty HTML is added
     * Example of such is a nav bar
     */
    public function addBodyElement($el) {
        $this->bodyElements[] = $el . "\n";
    }

    /**
     * function addTailElement($el)
     * adds an element to the body, but after all specialty HTML is added
     * Things that would be in the tail would be like a footer
     */
    public function addTailElement($el) {
        $this->tailElements[] = $el . "\n";
    }

    /** 
     * function beginHTML()
     * returns the modulated head and body data.
     * To be called before adding page-specific HTML
     */
    public function beginHTML() {
        $output = $this->head;
        foreach ($this->headElements as $he) {
            $output .= "\t" . $he . "\n";
        }

        $output .= $this->body;
        foreach ($this->bodyElements as $be) {
            $output .= "\t" . $be . "\n";
        }
        return $output;
    }

    /** 
     * function closeHTML()
     * returns the modulated "tail" (after body) data.
     * To be called after adding page-specific HTML
     */
    public function closeHTML() {
        $output = $this->tail;
        foreach ($this->tailElements as $te) {
            $output .= "\t" . $te . "\n";
        }
        $output .= "</body>";
        return $output;
    }

}


?>