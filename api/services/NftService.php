<?php
require_once __DIR__ . "/../dao/NftDao.php";

class NftService
{
    protected $dao;

    public function __construct()
    {
        $this->dao = new NftDao();
    }

    public function getAllNFTs($page)
    {
        return $this->dao->getAllNFTs($page);
    }

    public function getAllNFTsForAUserId($page, $userId)
    {
        return $this->dao->getAllNFTsForAUserId($page, $userId);
    }

    public function addNft($userid, $name, $imgUrl, $certificate, $description)
    {
        return $this->dao->addNft($userid, $name, $imgUrl, $certificate, $description);
    }

    public function editNft($nftId, $name, $description)
    {
        return $this->dao->editNft($nftId, $name, $description);
    }

    public function deleteNft($id)
    {
        return $this->dao->deleteNft($id);
    }
}
?>