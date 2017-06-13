<?php

class LoginController extends Controller {

    private $fb;
    private $user;
    private $friend;

    function __construct() {
        $this->fb = Fb::get();

        $this->user = new User();
        $this->friend = new Friend();

        $this->pushGuard( new AlreadyLoggedInGuard );
    }

    private function getFbUrl() {
        $helper = $this->fb->getRedirectLoginHelper();

        $permissions = ['email']; // Optional permissions
        $loginUrl = $helper->getLoginUrl(FB_LOGIN_URL, $permissions);

        return $loginUrl;
    }

    public function index() {

        $url = $this->getFbUrl();

        $this->render("login.index", [
            "title" => "Login",
            "fbUrl" => $url
        ]); 

    }

    public function loginCallback() {
        $this->fbCallback();
    }

    public function create() {

        $id = Session::exists('id')? Session::get('id') : 0;

        $user = $this->user->where('fb_id', '=', $id)->apply();

        if (empty($user) && Session::exists('userInfo')) {

            $this->render('user.create', [
                "title" => "Vytvoření profilu"
            ]);
            exit();

        }
        else if (!Session::exists('userInfo')) {
            UrlUtils::redirect('knihy');
        }
        else {
            $friends = $this->friend->getFriendsId($user['id']);

            Session::set('username', $user['username']);
            Session::set('email', $user['email']);
            Session::set('fb_id', $user['fb_id']);
            Session::set('id', $user['id']);
            Session::set('logged_in', true);
            Session::set('admin', $user['admin']);
            Session::set('friends', $friends);
            UrlUtils::redirect('/'.BASE_URL.'/zed');
        }
    }

    public function store() {
        
        if (!Session::exists("userInfo")) {
            $this->render('error', [
                "title" => "Chyba",
                "error" => "K této akci musíte být registrovaní"
            ]);
            exit();
        }

        $userInfo = Session::get("userInfo");
        
        $fb_id = $userInfo['id'];
        $email = $userInfo['email'];
        $picture = $userInfo['picture']['url'];

        $username = @Post::get("username");

        if (!preg_match("/[A-Za-z0-9]{4,}/", $username)) {
            $this->render('user.create', [
                "title" => "Vytvoření profilu",
                "errors" => ["username" => "Špatně zadané uživatelské jméno. Minimálně 4 znaky. Jen písmena a čísla."]
            ]);
            exit();
        }

        $user = $this->user->where("username", "=", $username)->apply();
        if (!empty($user)) {
            $this->render('user.create', [
                "title" => "Vytvoření profilu",
                "errors" => ["username" => "Uživatel s takovýmto jménem již existuje"]
            ]);
            exit();
        }
    
        $this->user->insertInto($fb_id, $username, $email, $picture);

        $user = $this->user->where("fb_id", "=", $fb_id)->apply();

        $friends = $this->friend->getFriendsId($user['id']);

        Session::set('username', $user['username']);
        Session::set('email', $user['email']);
        Session::set('fb_id', $user['fb_id']);
        Session::set('id', $user['id']);
        Session::set('admin', $user['admin']);
        Session::set('logged_in', true);
        Session::set('friends', $friends);


        UrlUtils::redirect('/'.BASE_URL.'/zed');

    }

    public function destroy() {
        if (Session::exists('id')) {
            Session::destroy();
        }
        UrlUtils::redirect("/".BASE_URL.'/knihy');
    }

    private function fbCallback() {

        $helper = $this->fb->getRedirectLoginHelper();

        try {
            $accessToken = $helper->getAccessToken();
            } catch(Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
            } catch(Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        if (! isset($accessToken)) {
            if ($helper->getError()) {
                header('HTTP/1.0 401 Unauthorized');
                echo "Error: " . $helper->getError() . "\n";
                echo "Error Code: " . $helper->getErrorCode() . "\n";
                echo "Error Reason: " . $helper->getErrorReason() . "\n";
                echo "Error Description: " . $helper->getErrorDescription() . "\n";
            } else {
                header('HTTP/1.0 400 Bad Request');
                echo 'Bad request';
            }
            exit;
        }


        $oAuth2Client = $this->fb->getOAuth2Client();

        // Get the access token metadata from /debug_token
        $tokenMetadata = $oAuth2Client->debugToken($accessToken);

        $tokenMetadata->validateAppId(Fb::getId());
        $tokenMetadata->validateExpiration();

        if (! $accessToken->isLongLived()) {
        // Exchanges a short-lived access token for a long-lived one
            try {
                $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
            } catch (Facebook\Exceptions\FacebookSDKException $e) {
                echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
                exit;
            }
        }

        Session::set('fb_access_token', (string) $accessToken);

        $token = Session::get('fb_access_token');

        $userInfo = Fb::getUserInfo($token);

        Session::set('userInfo', $userInfo);
        Session::set('id', $userInfo['id']);

        UrlUtils::redirect("./vytvorit/");

    }

}
    