<?php

class ArticleGuard implements IGuard {

    public function resolve(array $data) {
        if ($data["action"] !== "destroy") {
            return true;
        }
        else {
            return false;
        }
        
    }

}