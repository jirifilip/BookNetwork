<?php

    class AchievementController extends Controller {

        private $achievement;
        private $hasAch;
        private $friend;
        private $story;
        private $book;

        function __construct() {
            $this->achievement = new Achievement();
            $this->hasAch = new HasAchievement();
            $this->friend = new Friend();
            $this->story = new Story();
            $this->book = new Book();

            $this->pushGuard( new LoggedInGuard );
            $this->pushGuard( new NoRouteGuard(["create", "show", "load", "edit", "destroy"]) );
        }
    
        public function index() {
            $userId = Session::get('id');

            $currAchs = $this->hasAch->where("user_id", "=", $userId)->apply();

            if (count($currAchs) < 3) {
                for ($i = 1; $i <= 3; $i++) {
                    $this->hasAch->insert([
                        "user_id" => $userId,
                        "achievement_id" => $i,
                        "progress" => 5
                    ]);
                }
            }

            $achvs = $this->achievement->forUser($userId);

            $friends = $this->friend->getFriends($userId);
            if (!is_array(reset($friends))) {
                $friends = [$friends];
            }

            $books = $this->book->where("author_id", "=", $userId)->apply();
            if (!is_array(reset($books))) {
                $books = [$books];
            }

            $stories = $this->story->fromAuthor($userId);
            if (!is_array(reset($stories))) {
                $stories = [$stories];
            }

            $this->render("achievement.index", [
                "title" => "Seznam odznaků",
                "achvs" => $achvs,
                "counts" => [count($friends), count($books), count($stories)]
            ]);
        }

        public function store() {
            $achievementId = @Post::get('achievement_id');
            $userId = Session::get('id');

            if ($achievementId == 1) {
                $friends = $this->friend->getFriends($userId);
                $progress = $this->hasAch
                    ->where('user_id', '=', $userId)
                    ->where('achievement_id', '=', $achievementId)
                    ->apply();

                if (count($friends) >= $progress['progress']) {
                    $this->hasAch
                        ->update(
                            ["id", $progress['id']],
                            [ "progress" => $progress['progress'] + 5]
                        );
                    UrlUtils::previousPage();
                }
                
            }
            else if ($achievementId == 2) {
                $books = $this->book->where("author_id", "=", $userId)->apply();

                $progress = $this->hasAch
                    ->where('user_id', '=', $userId)
                    ->where('achievement_id', '=', $achievementId)
                    ->apply();

                if (count($books) >= $progress['progress']) {
                    $this->hasAch
                        ->update(
                            ["id", $progress['id']],
                            [ "progress" => $progress['progress'] + 5]
                        );
                    UrlUtils::previousPage();
                }
                
            }
            else if ($achievementId == 3) {
                $stories = $this->story->fromAuthor($userId);

                $progress = $this->hasAch
                    ->where('user_id', '=', $userId)
                    ->where('achievement_id', '=', $achievementId)
                    ->apply();

                if (count($stories) >= $progress['progress']) {
                    $this->hasAch
                        ->update(
                            ["id", $progress['id']],
                            [ "progress" => $progress['progress'] + 5]
                        );
                    UrlUtils::previousPage();
                }
            }
            
            URLUtils::previousPage();
        }

    }
    