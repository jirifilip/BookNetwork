<?php

class GuardResult {

    private $result;
    private $data;
    private $error;
    
    function __construct($result, $error="", $data=array()) {
        $this->result = $result;
        $this->data = $data;
        $this->error = $error;
    }

    public function get() {
        return [
            "result" => $this->result,
            "data" => $this->data,
            "error" => $this->error
        ];
    }

}