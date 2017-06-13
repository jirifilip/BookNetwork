<?php
    
    class AccessEveryoneGuard implements IGuard {
    
            public function resolve(array $data) {

                $action = $data["action"];
                $params = $data["params"];                

                if ($action == "show") {
                    $storyId = $params['id'];

                    $storyModel = new Story();
                    $bookModel = new Book();

                    $story = $storyModel->where('id', '=', $storyId)->apply();
                    $book = $bookModel->where('id', '=', @$story['book_id'])->apply();

                    $everyone = @$book['access_everyone'];

                    $route = $action == 'show';

                    if ($everyone == 0 && $route) {
                        if (Session::exists('id')) {
                            return new GuardResult(true);
                        } else {
                            return new GuardResult(false, "Pro prohlédnutí povídky se musíš přihlásit - autor omezil její přístup");
                        }
                        
                    } else {
                        return new GuardResult(true);
                    }
                } else {
                    return new GuardResult(true);
                }
            }

    }
    