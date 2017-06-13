<?php
    
    class BookAuthorGuard implements IGuard {
    
            public function resolve(array $data) {
                
                $action = $data["action"];
                $params = $data["params"];

                if (in_array($action, ["load", "upravit", "smazat"])) {
                     $bookId = $params['id'];
                     $userId = Session::get("id");

                     $user = new User();
                     $userFound = $user->where('id', "=", $userId)->apply();

                     $book = new Book();
                     $bookFound = $book
                        ->where("id", "=", $bookId)
                        ->where("author_id", "=", $userId)
                        ->apply();

                    if (!empty($bookFound) || $userFound['admin'] == 1) {
                        return new GuardResult(true);
                    } else {
                        return new GuardResult(false, "Musíš být autorem knihy, kterou si přeješ upravit nebo vymazat.");
                    }

                } else {
                    return new GuardResult(true);
                }
                
            }

    }
    