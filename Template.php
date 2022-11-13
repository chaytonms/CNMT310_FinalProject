<?php
// custom template class and html manager

class Template {

    protected $title;
    protected $head; //stuff in the head tags 
    protected $body; // stuff in the body tag before addition ie nav bars
    protected $tail; // stuff after additions to body, choses it, and adds footers
    
    // insert default header code here
    protected $headElements = array("<!doctype html>", 
                                    "<head lang=\"en\">",
                                    "<link href=\"https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css\" rel=\"stylesheet\" integrity=\"sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi\" crossorigin=\"anonymous\">",
                                    "<link href=\"global.css?" .  "\" rel=\"stylesheet\">",    
                                );
    
    // insert end of head + start of body here
    protected $bodyElements = array("</head>", "<body>");

    // add default end of body here
    protected $tailElements = array('<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>',
                                    
                                    );


    public function __construct($title) {
        $this->title = $title;
    }

    /**
     * function addHeadElements ($el)
     * Returns: String
     * adds element to head, such as a link or script tag
     * 
     */
    public function addHeadElement($el) {
        $this->headElements[] = $el . "\n";
    }

    /**
     * function addBodyElements ($el)
     * Returns: String
     * adds element to body, before any specialty HTML is added
     * Example of such is a nav bar
     */
    public function addBodyElement($el) {
        $this->bodyElements[] = $el . "\n";
    }

    /**
     * function addTailElement($el)
     * Returns: String
     * adds an element to the body, but after all specialty HTML is added
     * Things that would be in the tail would be like a footer
     */
    public function addTailElement($el) {
        $this->tailElements[] = $el . "\n";
    }

    /** 
     * function beginHTML()
     * Returns: String
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
     * Returns: String
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