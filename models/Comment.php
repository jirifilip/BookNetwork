<?php
    
    class Comment extends Model {
    
            function __construct() {
                $this->name = 'comment';

            }

            function validate($input) {
                $errors = [];
                
                if (!(strlen($input["text"]) > 20 && strlen($input["text"]) < 1000)) {
                    $errors["text"] = "Text musíš být větší než 20 znaků a zároveň menší než 1000.";
                }

                return $errors;
            }

            public function forStory($storyId) {
                $res = Db::queryAll(
                    "SELECT
                        comment.id,
                        user.picture_url,
                        comment.user_id,
                        comment.timestamp,
                        comment.upvotes,
                        comment.text,
                        comment.story_id
                    FROM user
                    INNER JOIN comment
                    ON (user.username = comment.user_id)
                    WHERE comment.story_id = ?",
                    [$storyId]
                );

                return $res;
            }

    }
    