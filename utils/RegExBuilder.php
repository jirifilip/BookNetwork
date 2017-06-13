<?php

class RegExBuilder {

    private $regEx = [];
    private $currentIndex = 0;
    
    function __construct() {

    }

    public function newRange() {
        $this->regEx[$this->currentIndex] = [
            "expression" => [],
            "min" => "",
            "max" => ""
        ];

        return $this;
    }

    public function done() {
        $regEx = array_reduce($this->regEx, function($acc, $curr) {
            $exp = array_reduce($curr["expression"], function($acc, $curr) { return $acc.$curr; });
            $exp = "[$exp]";


            $minMax = "";
            if (strlen($curr["min"].$curr["max"]) > 1) {
                $minMax = $curr["min"].",".$curr["max"];
                $minMax = "{".$minMax."}";
            }

            $exp = $exp.$minMax;

            return $acc . $exp;
        });

        $this->regEx = [];
        $this->currentIndex = 0;

        return $regEx;
    }

    public function exitRange($min="", $max="") {
        $this->regEx[$this->currentIndex]["min"] = $min;
        $this->regEx[$this->currentIndex]["max"] = $max;

        $this->currentIndex++;

        return $this;
    }

    public function charEnum(array $characters) {
        $content = array_reduce($characters, function ($acc, $curr) {
            return $acc . $curr;
        });
        $this->addExpression($content);

        return $this;
    }

    public function charRange(array $range) {
        if (count($range) == 2) {
            $exp = $range[0]."-".$range[1];
            $this->addExpression($exp);
            return $this;
        }
        else {
            return $this;
        }
    }

    public function char($char) {
        $this->addExpression($char);
    }

    private function addExpression($exp) {
        array_push($this->regEx[$this->currentIndex]["expression"], $exp);
    }
}