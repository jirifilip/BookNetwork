<?php

class HTMLBuilder {

    private $elementStack = [
        "tagName" => "",
        "id" => "",
        "class" => [],
        "attributes" => [],
        "content" => [],
        "pair" => true
    ];
    private $nonPairElements = ["br", "input", "hr"];
    
    public function element($tagName) {
        $this->addToStack("tagName", $tagName);
        
        if (in_array($tagName, $this->nonPairElements)) {
            $this->elementStack['pair'] = false;
        }
        
        return $this;
    }

    public function id($id) {
        $this->addToStack("id", $id);
        return $this;
    }

    public function className($class) {
        $this->addGroupToStack("class", $class);
        return $this;
    }
    public function attribute($attribute, $value="") {
        $final = gettype($attribute) == "array"? $attribute : "$attribute='$value'";
        $this->addGroupToStack("attributes", $final);
        return $this;
    }
    public function content($content) {
        $this->addGroupToStack("content", $content);
        return $this;
    }
    public function get() {
        $element = $this->toHTML();
        $this->resetStack();
        return $element;
    }
    
    
    private function toHTML() {
        $stack = $this->elementStack;

        $tagName = $stack["tagName"];
        
        $id = $stack["id"];
        $id = empty($id) ? "" : "id='$id'";

        $class = $this->groupToHTML($stack["class"]);
        $class = empty($class) ? "" : "class='$class'";
        $class = trim($class);

        $attributes = $this->groupToHTML($stack["attributes"]);
        $attributes = trim($attributes);

        $content = $this->groupToHTML($stack["content"]);
        $content = trim($content);

        $element;
        if ($stack["pair"]) {
            $element = "<$tagName $id $class $attributes>$content</$tagName>";
        }
        else {
            $element = "<$tagName $id $class $attributes />";
        }
        

        return $element;

    }

    private function groupToHTML($group) {
        $string = "";
        foreach ($group as $member) {
            $string .= "$member ";
        }
        return $string;
    }

    private function resetStack() {
        $this->elementStack = [
            "tagName" => "",
            "id" => "",
            "class" => [],
            "attributes" => [],
            "content" => [],
            "pair" => true
        ];
    }



    private function addToStack($key, $value) {
        $this->elementStack[$key] = $value;
    }
    private function addGroupToStack($key, $group) {
        if (gettype($group) == "array") {
            foreach($group as $val) {
                array_push($this->elementStack[$key], $val);
            }
        }
        else {
            array_push($this->elementStack[$key], $group);
        }
    }


}