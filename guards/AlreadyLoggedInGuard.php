<?php
    
class AlreadyLoggedInGuard implements IGuard {

        public function resolve(array $data) {
            if (!Session::exists('id') || in_array($data['action'], ["destroy", "create", "store"])) {
                return new GuardResult(true);
            } else {
                return new GuardResult(false, "Už jsi se přihlásil!");
            }
        }

}
