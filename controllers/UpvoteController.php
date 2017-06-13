<?php

    class UpvoteController extends Controller {

        private $upvoteModel;
        private $idModel;

        function __construct() {
            $this->upvoteModel = [
                "book" => new Book(),
                "story" => new Story(),
                "comment" => new Comment()
            ];
            $this->idModel = [
                "book" => [
                    "model" => new UpvoteBook,
                    "id" => "book_id"   
                ],
                "story" => [
                    "model" => new UpvoteStory,
                    "id" => "story_id"   
                ],
                "comment" => [
                    "model" => new UpvoteComment,
                    "id" => "comment_id"   
                ],
            ];

            $this->pushGuard( new LoggedInGuard );

            $this->pushGuard(
                new NoRouteGuard(["index", "create", "show", "store", "edit", "load"])
            );
        }
    
        public function destroy() {
            $type = @Post::get('type');
            $id = @Post::get('id');
            $vote = @Post::get('vote');
            $currentVote = $vote;

            if (!is_numeric($id)) {
                $this->error([
                    "title" => "Chyba",
                    "error" => "ID musí být číslo"
                ]);

                exit();
            }

            if (!in_array($type, ['book', 'story', 'comment'])) {
                $this->error([
                    "title" => "Chyba",
                    "error" => "Špatný typ pro hlasování"
                ]);

                exit();
            }

            if (!in_array($vote, [-1, 1])) {
                $this->error([
                    "title" => "Chyba",
                    "error" => "Nemůžeš hlasovat takovýmto počtem"
                ]);

                exit();
            }

            $model = $this->idModel[$type];

            $findVote = $model['model']
                ->where($model['id'], "=", $id)
                ->where("user_id", "=", Session::get('id'))
                ->apply();

            if (!empty($findVote)) {
                $voted = true;
                $vote += $findVote['vote'] == -1? 1 : -1;
            } else {
                $voted = false;
            }

            $object = $this->upvoteModel[$type]->where('id', "=", $id)->apply();

            $upvoteVal = $object['upvotes'] + $vote;

            $this->upvoteModel[$type]->update(
                ["id", $id],
                ["upvotes" => $upvoteVal]
            );

            

            if ($voted == false) {
                $model["model"]
                    ->insert([
                        "user_id" => Session::get("id"),
                        $model['id'] => $id,
                        "vote" => $currentVote
                    ]);
            } else {
                $model["model"]
                    ->update(
                        [ "id", $findVote['id'] ],
                        ["vote" => $currentVote]
                    );
            }

            URLUtils::previousPage(); 
        }

    }
    