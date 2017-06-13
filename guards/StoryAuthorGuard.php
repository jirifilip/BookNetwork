<?php

class StoryAuthorGuard implements IGuard {

        public function resolve(array $data) {
            
            $action = $data["action"];
            $params = $data["params"];

            if (in_array($action, ["load", "upravit", "smazat"])) {
                $storyId = $params['id'];
                $userId = Session::get("id");

                $user = new User();
                $userFound = $user->where('id', "=", $userId)->apply();

                $story = new Story();
                $storyFound = Db::queryAll(
                    "SELECT * FROM story
                    INNER JOIN book
                    ON (story.book_id = book.id)
                    WHERE book.author_id = ?
                    AND story.id = ?",
                    [$userId, $storyId]
                );

                if (!empty($storyFound) || $userFound['admin'] == 1) {
                    return new GuardResult(true);
                } else {
                    return new GuardResult(false, "Musíš být autorem povídky, kterou si přeješ upravit nebo vymazat.");
                }

            } else {
                return new GuardResult(true);
            }
            
        }

}
    