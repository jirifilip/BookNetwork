<?php
    
class EmptyCharacterGuard implements IGuard {

        public function resolve(array $data) {
            $action = $data['action'];

            $user = new User();
            $username = Session::get("username");
            $res = $user->where("username", "=", $username)->apply();

            if (empty($res["character_data"])) {
                return new GuardResult(false, "Nejdřív si musíš vytvořit postavu");
            }
            else {
                return new GuardResult(true);
            }
        }

}