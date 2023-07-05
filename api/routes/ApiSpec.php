<?php


/**
 * @OA\Info(title="Web project", version="0.1", @OA\Contact(email="web", name="Web"))
 * @OA\OpenApi(
 *    @OA\Server(url="http://localhost/project/api", description="Development Environment" ),
 *    @OA\Server(url="https://nft-social-media-4eefe819eec9.herokuapp.com/api", description="Production Environment" )
 * ),
 * @OA\SecurityScheme(securityScheme="ApiKeyAuth", type="apiKey", in="header", name="Authorization" )
 */

?>