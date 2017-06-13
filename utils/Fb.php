<?php

class Fb {

    static $id = '1838454273147219';
    static $secret = 'd0fc62e26ddac60cbe45e300129d062b';

    static function get() {
        return new Facebook\Facebook([
            'app_id' => self::$id, // TODO Replace {app-id} with your app-id
            'app_secret' => self::$secret, // TODO Replace {app-secret} with your app-secret
            'default_graph_version' => 'v2.2',
        ]);
    }

    static function getId() {
        return self::$id;
    }

    static function getSecret() {
        return self::$secret;
    }
    
    static function getUserInfo($accessToken) {
        $fb = self::get();

        
        try {
            $response = $fb->get('/me?fields=id,email,picture', $accessToken);
            } catch(Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
            } catch(Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        $user = $response->getGraphUser();

        return $user;
    }
}