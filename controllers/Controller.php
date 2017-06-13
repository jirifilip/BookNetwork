<?php

abstract class Controller {

    protected $guards = array();
    protected $params;
    protected $data = array(
        "title" => "",
    );



    public function render($view, $data=array()) {
        extract($data);
        require("views/$view.phtml");
    }

    public function index() {}
    public function create() {}
    public function show() {}
    public function store() {}
    public function edit() {}
    public function destroy() {}
    public function error($data) {
        $this->render('error', $data);
    }

        
    public function run($action) {
        $guardData = [
            "action" => $action,
        ];

        $guardReturn = $this->resolveGuard($guardData);

        $canGo = true;
        $guardData = [];

        if (!empty($guardReturn)) {
            $canGo = $guardReturn[0];
            $guardData = array_shift($guardReturn[1]);
        };

        if ($canGo && method_exists($this, $action)) {
            $this->$action();
        }
        else {
            # KDYŽ BUDU HLEDAT CHYBU SE ZOBRAZOVÁNÍM CHYB
            # JE URČITĚ TADY
            # BERU TU VŽDYCKY JENOM PRVNÍ Z CELÉHO POLE CHYB
            $this->error($guardData);
        }
    }

    private function resolveGuard($guardData) {
        if (!empty($this->guards)) {
            $guardResolver = new GuardResolver($this->guards, $guardData);
            return $guardResolver->resolve();
        }
        else {
            return [];
        }
        
    }

    //gettery
    public function getData() {
        return $this->data;
    }
    public function getParams() {
        return $this->params;
    }

    //settery
    public function setData($data) {
        $this->data = $data; 
    }
    public function setParams($params) {
        $this->params = $params;
    }


    //guards
    public function pushGuard($guard) {
        array_push($this->guards, $guard);
    }

}