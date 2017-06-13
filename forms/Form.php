<?php

class Form {

    protected $builder;
    protected $validator;
    protected $content = [];
    protected $rendered;
    protected $method;
    protected $action;
    protected $id;
    protected $class;

    function __construct($method, $action="", $id="", $class="") {
        $this->builder = new HTMLBuilder();
        # $this->validator = new Validator();

        $this->method = $method;
        $this->action = $action;
        $this->id = $id;
        $this->class = $class;
    }


    public function render() {
        return $this->rendered;
    }
    public function validate() {
        $this->validator->validate($this->content);
    }
    public function done() {
        $this->toHTML();
    }

    private function toHTML() {
        $content = array_reduce($this->content, function($acc, $curr) {
            $curr = $curr["content"];
            return "$acc $curr";
        });

        $form = $this->builder
                        ->element("form")
                        ->attribute("method", $this->method)
                        ->attribute("action", $this->action)
                        ->id($this->id)
                        ->className($this->class)
                        ->content($content)
                        ->get();

        $this->rendered = $form;
        return $form;
    }


    public function select($name, $options=array()) {
        $optElement = $this->options($options);

        $select = $this->builder
                    ->element("select")
                    ->attribute("name", $name)
                    ->content($optElement)
                    ->get();

        $this->addToStack($name, $name, $select);    
    }

    // generování options do datalistu nebo selectu
    protected function options($options=array()) {
        $optElement = [];

        foreach ($options as $key => $option) {
            $temp = $this->builder
                        ->element("option")
                        ->attribute("value", $key)
                        ->content($option)
                        ->get();
            array_push($optElement, $temp);
        }
        
        return $optElement;
    }

    public function textArea($name, $class=[], $id="", $options=array(
        "rows" => 10,
        "cols" => 30,
        "value" => ""
    )) {

        $rows = $options["rows"];
        $cols = $options["cols"];
        $value = $options['value'];
        
        $textArea = $this->builder
                        ->element("textarea")
                        ->id($id)
                        ->className($class)
                        ->attribute("name", $name)
                        ->attribute("rows", $rows)
                        ->attribute("cols", $cols)
                        ->content($value)
                        ->get();

        $this->addToStack($name, $textArea); 
    }

    public function button($value, $id="", $class="") {
        $button = $this->builder
                        ->element("button")
                        ->id($id)
                        ->className($class)
                        ->content($value)
                        ->get();
        
        $this->addToStack("submit", $button); 
    }

    public function datalist($name, $options=array()) {
        $optElement = $this->options($options);

        $input = $this->builder
                        ->element("input")
                        ->attribute("list", $name)
                        ->get();

        $datalist = $this->builder
                    ->element("datalist")
                    ->id($name)
                    ->content($optElement)
                    ->get();

        $datalist = $input . $datalist;

        $this->addToStack($name, $datalist);  
    }

    public function textInput($name, $id="", $class=array()) {
        $input = $this->input("text", $name, $id, $class);

        $this->addToStack($name, $input);
    }

    public function passwordInput($name, $id="", $class=array()) {
        $input = $this->input("password", $name, $id, $class);

        $this->addToStack($name, $input);
    }

    public function emailInput($name, $id="", $class=array()) {
        $input = $this->input("email", $name, $id, $class);

        $this->addToStack($name, $input);
    }

    public function colorInput($name, $id="", $class=array()) {
        $input = $this->input("color", $name, $id, $class);

        $this->addToStack($name, $input);
    }

    public function dateInput($name, $id="", $class=array()) {
        $input = $this->input("date", $name, $id, $class);

        $this->addToStack($name, $input);
    }

    public function rangeInput($name, $id, $min, $max, $class=array(), $value=0) {
        $input = $this->builder
                    ->element("input")
                    ->id($id)
                    ->className($class)
                    ->attribute("min", $min)
                    ->attribute("max", $max)
                    ->attribute("name", $name)
                    ->attribute("type", "range")
                    ->attribute("value", 0)
                    ->get();

        $this->addToStack($name, $input);
    }

    public function label($content, $id="", $class=array()) {
        $label = $this->builder
                    ->element("label")
                    ->id($id)
                    ->className($class)
                    ->content($content)
                    ->get();

        $this->addToStack("label" . count($this->content), $label);
    }

    public function br() {
         $this->addToStack("br" . count($this->content), "<br />");
    }

    public function searchInput($name, $id="", $class=array()) {
        $input = $this->input("search", $name, $id, $class);

        $this->addToStack($name, $input);
    }

    public function radioInput($name, $class=array(), $values=array(), $text=array(), $checked="") {
        $radioInputs = [];

        foreach ($values as $index => $val) {
            $input = $this->input("radio", $name, "", $class, $val) . "  " . $text[$index];
            array_push($radioInputs, $input);
        }

        $inputs = array_reduce($radioInputs, function($acc, $curr) {
            return $acc .= " $curr";
        });

        $this->addToStack($name, $inputs);
    }

    # pro zobecnění inputů
    private function input($type, $name, $id, $class, $value="") {
        $input = $this->builder
                    ->element("input")
                    ->id($id)
                    ->className($class)
                    ->attribute("name", $name)
                    ->attribute("type", $type)
                    ->attribute("value", $value)
                    ->get();

        return $input;
    }


    private function addToStack($name, $content) {
        array_push($this->content, [
            "name" => $name,
            "validation" => "",
            "content" => $content
        ]);
    }

}