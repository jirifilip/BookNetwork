<?php
    
    class NewBookGuard implements IGuard {
    
            public function resolve(array $data) {
                $action = $data['action'];

                if ($action == "index" || $action == "show" || Session::exists('id')) {
                    return new GuardResult(true);
                } else {
                    return new GuardResult(false, "K této akci se musíte přihlásit");
                }
            }

    }
    