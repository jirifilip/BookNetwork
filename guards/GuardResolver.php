<?php

class GuardResolver {

    private $guards;

    function __construct(array $guards, array $data) {
        $this->guards = $guards;
        $this->data = $data;
    }

    public function resolve() {
        $data = $this->data;

        if (!empty($this->guards)) {
            $resolved = array_map( function($guard) {
                return $guard
                    ->resolve($this->data)
                    ->get()["result"];
            }, $this->guards);

            $otherData = array_map( function ($guard) {
                $res = $guard->resolve($this->data)->get();
                return [
                    "error" => $res["error"],
                    "data" => $res["data"]
                ];
            }, $this->guards);

            $canGo = !in_array(false, $resolved);

            return [$canGo, $otherData];

        }

        return true;
    }

}