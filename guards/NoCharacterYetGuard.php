<?php
    
class NoCharacterYetGuard implements IGuard {

        public function resolve(array $data) {
            $action = $data['action'];

            $user = new User();
            $username = Session::get("username");
            $res = $user->where("username", "=", $username)->apply();

            if (($action == "create" || $action == "store" || $action == "index") && empty($res["character_data"])) {
                return new GuardResult(true);
            }
            else if ($action == "edit" || $action == "destroy") {
                return new GuardResult(true);
            }
            else {
                return new GuardResult(false, "Postavu již máte vytvořenou");
            }
        }

}
    