<?php

/**
 * @OA\Post(
 *     path="/signup",
 *     tags={"auth"},
 *     description="Sign up a new user",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"username", "email", "password"},
 *             @OA\Property(property="username", type="string"),
 *             @OA\Property(property="email", type="string"),
 *             @OA\Property(property="password", type="string", format="password")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User signed up successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string"),
 *             @OA\Property(property="token", type="string"),
 *             @OA\Property(property="userId", type="integer")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error: Username or email already exists",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string")
 *         )
 *     )
 * )
 */
Flight::route('POST /signup', function () {
    $request = Flight::request();
    $username = $request->data->username;
    $email = $request->data->email;
    $password = $request->data->password;

    $userService = Flight::userService();
    $response = $userService->signUpUser($username, $email, $password);

    Flight::json($response, $response['code']);
});

/**
 * @OA\Post(
 *     path="/signin",
 *     tags={"auth"},
 *     description="Sign in a user",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email", "password"},
 *             @OA\Property(property="email", type="string"),
 *             @OA\Property(property="password", type="string", format="password")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="User signed in successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="user", type="object"),
 *             @OA\Property(property="message", type="string"),
 *             @OA\Property(property="token", type="string")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error: User does not exist or blocked or incorrect password",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string")
 *         )
 *     )
 * )
 */
Flight::route('POST /signin', function () {
    $request = Flight::request();
    $email = $request->data->email;
    $password = $request->data->password;

    $userService = Flight::userService();
    $result = $userService->signInUser($email, $password);

    Flight::json($result, $result['code']);
});


/**
 * @OA\Get(
 *     path="/users-leaderboard/{page}",
 *     tags={"users"},
 *     description="Get leaderboard of users",
 *     security={{"ApiKeyAuth": {}}},
 *     @OA\Parameter(
 *         name="page",
 *         in="path",
 *         description="Page number for pagination",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 @OA\Property(property="username", type="string"),
 *                 @OA\Property(property="email", type="string"),
 *                 @OA\Property(property="userId", type="integer"),
 *                 @OA\Property(property="numOfpublishedNfts", type="integer")
 *             )
 *         )
 *     )
 * )
 */
Flight::route('GET /users-leaderboard/@page', function ($page) {
    $userService = Flight::userService();
    $users = $userService->getAllUsersForLeaderboard($page);
    Flight::json($users);
});

?>