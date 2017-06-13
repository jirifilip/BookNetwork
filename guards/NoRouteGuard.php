<?php
    
    class NoRouteGuard implements IGuard {
    
            private $routeArray;

            function __construct($routeArray) {
                $this->routeArray = $routeArray;
            }

            public function resolve(array $data) {

                $action = $data["action"];

                if (in_array($action, $this->routeArray)) {
                    return new GuardResult(false, "Takováto stránka neexistuje");
                } else {
                    return new GuardResult(true);
                }
            }

    }
    