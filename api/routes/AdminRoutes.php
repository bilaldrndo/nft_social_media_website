<?php
/**
 * @OA\Get(
 *     path="/admin/users/{page}",
 *     tags={"admin"},
 *     description="Get a paginated list of users",
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
 *     )
 * )
 */
Flight::route('GET /admin/users/@page', function ($page) {
    $adminService = Flight::adminService();
    $users = $adminService->getAllUsers($page);
    Flight::json($users);
});


/**
 * @OA\Post(
 *     path="/admin/block/{userId}",
 *     tags={"admin"},
 *     description="Block a user",
 *     security={{"ApiKeyAuth": {}}},
 *     @OA\Parameter(
 *         name="userId",
 *         in="path",
 *         description="ID of the user to block",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *     )
 * )
 */
Flight::route('POST /admin/block/@userId', function ($userId) {
    $adminService = Flight::adminService();
    $response = $adminService->blockUser($userId);
    Flight::json($response);
});

/**
 * @OA\Post(
 *     path="/admin/unblock/{userId}",
 *     tags={"admin"},
 *     description="Unblock a user",
 *     security={{"ApiKeyAuth": {}}},
 *     @OA\Parameter(
 *         name="userId",
 *         in="path",
 *         description="ID of the user to unblock",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *     )
 * )
 */
Flight::route('POST /admin/unblock/@userId', function ($userId) {
    $adminService = Flight::adminService();
    $response = $adminService->unblockUser($userId);
    Flight::json($response);
});

?>