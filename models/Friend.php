<?php
    
    class Friend extends Model {
    
            function __construct() {
                $this->name = 'friends';
            }

            public function getFriends($id) {
                $res1 = Db::queryAll(
                    "SELECT
                        user.username,
                        friends.friendship_id
                    FROM $this->name
                    INNER JOIN user
                    ON (user.id = $this->name.user_id2)
                    WHERE $this->name.user_id1=?
                    AND pending = 0",
                    [$id]
                );

                $res2 = Db::queryAll(
                    "SELECT
                        user.username,
                        friends.friendship_id
                    FROM $this->name
                    INNER JOIN user
                    ON (user.id = $this->name.user_id1)
                    WHERE $this->name.user_id2=?
                    AND pending = 0",
                    [$id]
                );

                $res = array_merge($res1, $res2);

                return $res;
            }

            public function getRequests($id) {
                $res = Db::queryAll(
                    "SELECT *
                    FROM $this->name
                    INNER JOIN user
                    ON (user.id = $this->name.user_id1)
                    WHERE $this->name.user_id2=?
                    AND pending = 1"
                , [$id]);

                return $res;
            }

            public function alreadyFriends($id1, $id2) {
                $stmt = "SELECT * FROM friends WHERE user_id1 = ? AND user_id2 = ?";

                $firstComb = Db::queryAll($stmt, [$id1, $id2]);
                $secondComb = Db::queryAll($stmt, [$id2, $id1]);

                if (count($firstComb) > 0 || count($secondComb) > 0) {
                    return true;
                } else {
                    return false;
                }
            }

            public function getFriendsId($id) {
                $res = Db::queryAll(
                    "SELECT user.id
                    FROM $this->name
                    INNER JOIN user
                    ON (user.id = $this->name.user_id2)
                    WHERE $this->name.user_id1=?
                    "
                , [$id]);

                return $res;
            }

    }
    