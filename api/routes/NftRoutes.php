<?php
/**
 * @OA\Get(
 *     path="/nfts/{page}",
 *     tags={"nfts"},
 *     description="Get a paginated list of NFTs",
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
 *         description="OK"
 *     )
 * )
 */
Flight::route('GET /nfts/@page', function ($page) {
    $nftService = Flight::nftService();
    $nfts = $nftService->getAllNFTs($page);
    Flight::json($nfts);
});


/**
 * @OA\Get(
 *     path="/user-nfts/{page}/{userid}",
 *     tags={"nfts"},
 *     description="Get a paginated list of user's NFTs",
 *     security={{"ApiKeyAuth": {}}},
 *     @OA\Parameter(
 *         name="page",
 *         in="path",
 *         description="Page number for pagination",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="userid",
 *         in="path",
 *         description="ID of the user",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *     )
 * )
 */
Flight::route('GET /user-nfts/@page/@userid', function ($page, $userid) {
    $nftService = Flight::nftService();
    $nfts = $nftService->getAllNFTsForAUserId($page, $userid);
    Flight::json($nfts);

});


/**
 * @OA\Post(
 *     path="/nfts",
 *     tags={"nfts"},
 *     description="Add a new NFT",
 *     security={{"ApiKeyAuth": {}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"userid", "name", "imgUrl", "certificate", "description"},
 *             @OA\Property(property="userid", type="integer"),
 *             @OA\Property(property="name", type="string"),
 *             @OA\Property(property="imgUrl", type="string"),
 *             @OA\Property(property="certificate", type="string"),
 *             @OA\Property(property="description", type="string")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="New NFT added",
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Error: NFT with this Certificate already exists",
 *     )
 * )
 */
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


/**
 * @OA\Post(
 *     path="/nfts/{id}",
 *     tags={"nfts"},
 *     description="Update an existing NFT",
 *     security={{"ApiKeyAuth": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of the NFT",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"name", "description"},
 *             @OA\Property(property="name", type="string"),
 *             @OA\Property(property="description", type="string")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="NFT successfully edited",
 *     )
 * )
 */
Flight::route('POST /nfts/update/@id', function ($id) {
    $name = Flight::request()->data['name'];
    $description = Flight::request()->data['description'];

    $nftService = Flight::nftService();
    $nfts = $nftService->editNft($id, $name, $description);

    Flight::json($nfts);
});


/**
 * @OA\Delete(
 *     path="/nfts/{id}",
 *     tags={"nfts"},
 *     description="Delete an NFT",
 *     security={{"ApiKeyAuth": {}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of the NFT",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="NFT deleted successfully",
 *     )
 * )
 */
Flight::route('DELETE /nfts/@id', function ($id) {
    $nftService = Flight::nftService();
    $response = $nftService->deleteNft($id);
    Flight::json($response);
});


?>