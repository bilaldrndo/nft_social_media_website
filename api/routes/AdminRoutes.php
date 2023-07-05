<?php

Flight::route('GET /admin/users/@page', function ($page) {
    $adminService = Flight::adminService();
    $users = $adminService->getAllUsers($page);
    Flight::json($users);
});

Flight::route('POST /admin/block/@userId', function ($userId) {
    $adminService = Flight::adminService();
    $response = $adminService->blockUser($userId);
    Flight::json($response);
});

Flight::route('POST /admin/unblock/@userId', function ($userId) {
    $adminService = Flight::adminService();
    $response = $adminService->unblockUser($userId);
    Flight::json($response);
});

?>