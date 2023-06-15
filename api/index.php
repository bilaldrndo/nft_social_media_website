<?php
require "../vendor/autoload.php";
require "dao/UserDao.php";
require "dao/NftDao.php";

session_start(); // Start the session

$requireLogin = function () {
    if (!isset($_SESSION['user_id'])) {
        Flight::redirect('/login');
    }
};

Flight::register('userDao', "UserDao");
Flight::register('nftDao', "NftDao");

Flight::route('GET /nfts/@page', function ($page) {
    $nftDao = Flight::nftDao();
    $nfts = $nftDao->getAllNFTs($page);
    Flight::json($nfts);
});

Flight::route('POST /nfts/update/@id', function ($id) {
    $name = Flight::request()->data['name'];
    $description = Flight::request()->data['description'];

    $nftDao = Flight::nftDao();
    $nfts = $nftDao->editNft($id, $name, $description);

    Flight::json($nfts);
});

Flight::route('DELETE /nfts/@id', function ($id) {
    $nftDao = Flight::nftDao();
    $response = $nftDao->deleteNft($id);
    Flight::json($response);
});

Flight::route('GET /user-nfts/@page/@userid', function ($page, $userid) {
    $nftDao = Flight::nftDao();
    $nfts = $nftDao->getAllNFTsForAUserId($page, $userid);
    Flight::json($nfts);

});

Flight::route('POST /nfts', function () {
    $userid = Flight::request()->data['userid'];
    $name = Flight::request()->data['name'];
    $imgUrl = Flight::request()->data['imgUrl'];
    $certificate = Flight::request()->data['certificate'];
    $description = Flight::request()->data['description'];

    $nftDao = Flight::nftDao();
    $response = $nftDao->addNFT($userid, $name, $imgUrl, $certificate, $description);

    Flight::json($response, $response['code']);
});


Flight::route('GET /users/@page', function ($page) {
    $userDao = Flight::userDao();
    $users = $userDao->getAllUsers($page);
    Flight::json($users);
});

Flight::route('GET /users-leaderboard/@page', function ($page) {
    $userDao = Flight::userDao();
    $users = $userDao->getAllUsersForLeaderboard($page);
    Flight::json($users);
});


Flight::route('POST /signup', function () {
    $request = Flight::request();
    $username = $request->data->username;
    $email = $request->data->email;
    $password = $request->data->password;

    $userDao = Flight::userDao();
    $response = $userDao->signUpUser($username, $email, $password);

    Flight::json($response, $response['code']);
});

Flight::route('POST /signin', function () {
    $request = Flight::request();
    $email = $request->data->email;
    $password = $request->data->password;

    $userDao = Flight::userDao();
    $result = $userDao->signInUser($email, $password);

    Flight::json($result, $result['code']);
});

// Example route for changing user username
Flight::route('PUT /users/@userId', function ($userId) {
    $request = Flight::request();
    $newUsername = $request->data->newUsername;

    $userDao = Flight::userDao();
    $result = $userDao->changeUsername($userId, $newUsername);

    if ($result) {
        Flight::json(['message' => 'Username changed successfully']);
    } else {
        Flight::json(['message' => 'Failed to change username'], 500);
    }

});

Flight::start();
?>