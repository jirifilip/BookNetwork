<?php

class UpvoteAPI extends REST {

    function __construct() {
        $this->route = "subscription";
        $this->subscription = new Subsription();
    }

    public function getAll() {
        $subscription = $this->subscription->all();
        
        echo $this->json($subscription);
    }

    public function getOne() {

    }

    public function post() {}

    public function put() {}

    public function delete() {}

}