<?php

Flight::route('GET /nfts/@page', function ($page) {
    $nftService = Flight::nftService();
    $nfts = $nftService->getAllNFTs($page);
    Flight::json($nfts);
});

Flight::route('POST /nfts/update/@id', function ($id) {
    $name = Flight::request()->data['name'];
    $description = Flight::request()->data['description'];

    $nftService = Flight::nftService();
    $nfts = $nftService->editNft($id, $name, $description);

    Flight::json($nfts);
});

Flight::route('DELETE /nfts/@id', function ($id) {
    $nftService = Flight::nftService();
    $response = $nftService->deleteNft($id);
    Flight::json($response);
});

Flight::route('GET /user-nfts/@page/@userid', function ($page, $userid) {
    $nftService = Flight::nftService();
    $nfts = $nftService->getAllNFTsForAUserId($page, $userid);
    Flight::json($nfts);

});

Flight::route('POST /nfts', function () {
    $userid = Flight::request()->data['userid'];
    $name = Flight::request()->data['name'];
    $imgUrl = Flight::request()->data['imgUrl'];
    $certificate = Flight::request()->data['certificate'];
    $description = Flight::request()->data['description'];

    $nftService = Flight::nftService();
    $response = $nftService->addNFT($userid, $name, $imgUrl, $certificate, $description);

    Flight::json($response, $response['code']);
});


?>