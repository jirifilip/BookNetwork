<?php

class UpvoteAPI extends REST {

    function __construct() {
        $this->route = "upvote";
        $this->story = new Story();
    }

    public function getAll() {
        $story = $this->story->where("id", "=", "1")->apply();
        
        echo $this->json($story);
    }

    public function getOne() {

    }

    public function post() {}

    public function put() {}

    public function delete() {}

}