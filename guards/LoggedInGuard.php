<?php
    
    class LoggedInGuard implements IGuard {
    
            public function resolve(array $data) {
                if (Session::exists('id')) {
                    $id = Session::get("id");
                    $user = (new User)->where("id", "=", $id)->apply();

                    if (!empty($user)) {
                        return new GuardResult(true);
                    } else {
                        Session::destroy();
                        return new GuardResult(false, "Takovýto uživatel neexistuje");
                    }
                    
                } else {
                    return new GuardResult(false, "K této akci se musíš přihlásit!");
                }
            }

    }
    