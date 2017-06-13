<?php

class FormValidator {

    private $form;
    private $cond = [
        "passMin" => "8",
        "passMax" => "",
        "usernameMin" => "4",
        "usernameMax" => "20"
    ];
    private $reBuilder;
    private $result;

    function __construct($form) {
        $this->form = $form;
        $this->reBuilder = new RegExBuilder();
    }

    public function validate() {
        if (!empty($_POST)) {

        }
    }

    
    private function regEx($regEx, $string) {
        return preg_match($regEx, $string);
    }

    private function length($string, $min=0, $max=99999) {
        $cond = strlen($string) >= $min && strlen($string) <= $max;
        return $cond;
    }

    private function filterMail($mail) {
        return filter_var($mail, FILTER_VALIDATE_EMAIL);
    }

    private function standardText($text, $min=1, $max=99999) {
        $pattern = $this->reBuilder
                            ->newRange()
                            ->char("A-Za-z0-9@_.-/\/")
                            ->exitRange($min, $max)
                            ->done();

        return $this->regEx("/$pattern/", $string);
    }

}