<?php

abstract class REST {

    protected $route;
    protected $params = [];

    public function run($action) {

        $canGo = true;

        if ($action == "get" && $canGo) {
            $this->getAll();
        }
        else if ($action == "getOne" && $canGo) {
            $this->getOne();
        }
        else if ($action == "post" && $canGo) {
            $this->post();
        }
        else if ($action == "put" && $canGo) {
            $this->put();
        }
        else if ($action == "delete" && $canGo) {
            $this->delete();
        }
    }

    public function getAll() {}

    public function getOne() {}

    public function post() {}

    public function put() {}

    public function delete() {}

    public function getRoute() {
        return $this->route;
    }

    public function setParams($params) {
        $this->params = $params;
    }

    protected function json($content) {
        header('Content-type: application/json');
        
        $content = json_encode($content, JSON_UNESCAPED_UNICODE);
        
        return $content; 
    }

}