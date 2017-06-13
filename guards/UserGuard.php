<?php
    
    class UserGuard implements IGuard {
    
            public function resolve(array $data) {
                
                if (Session::exists("username")) {
                    return new GuardResult(true);
                }
                else {
                    return new GuardResult(false, "Tato funkce je dostupná jen přihlášeným uživatelům");
                }

            }

    }
    