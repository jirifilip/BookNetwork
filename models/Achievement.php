<?php
    
    class Achievement extends Model {
    
            function __construct() {
                $this->name = 'achievement';
            }

            public function forUser($userId) {
                $res = Db::queryAll(
                    "SELECT
                        a.id as achievement_id,
                        a.name as achievement_name,
                        a.description as achievement_desc,
                        has_a.progress as progress
                     FROM $this->name as a
                     LEFT JOIN has_achievement as has_a
                     ON (a.id = has_a.achievement_id)
                     WHERE has_a.user_id = ?
                     ORDER BY a.id",
                     [$userId]
                );

                return $res;
            }
    }
    