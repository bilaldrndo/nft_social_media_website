<?php

Flight::route('POST /signup', function () {
    $request = Flight::request();
    $username = $request->data->username;
    $email = $request->data->email;
    $password = $request->data->password;

    $userService = Flight::userService();
    $response = $userService->signUpUser($username, $email, $password);

    Flight::json($response, $response['code']);
});

Flight::route('POST /signin', function () {
    $request = Flight::request();
    $email = $request->data->email;
    $password = $request->data->password;

    $userService = Flight::userService();
    $result = $userService->signInUser($email, $password);

    Flight::json($result, $result['code']);
});

Flight::route('GET /users-leaderboard/@page', function ($page) {
    $userService = Flight::userService();
    $users = $userService->getAllUsersForLeaderboard($page);
    Flight::json($users);
});

?>