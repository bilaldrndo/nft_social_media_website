<?php

class NftDao
{
    private $db;

    public function __construct()
    {
        $host = '127.0.0.1';
        $database = 'nft_db';
        $username = 'root';
        $password = '28012003';

        // Establish database connection
        $dsn = "mysql:host=$host;dbname=$database";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        $this->db = new PDO($dsn, $username, $password, $options);
    }

    public function getAllNFTs($page)
    {
        $limit = 15;
        $offset = ($page - 1) * $limit;

        // $stmt = $this->db->prepare("SELECT * FROM nfts ORDER BY id DESC LIMIT :limit OFFSET :offset");
        $stmt = $this->db->prepare("SELECT nfts.id as nftId, users.id as userId, users.username, nfts.name, nfts.image, nfts.certificate, nfts.description FROM nfts JOIN users ON nfts.userid=users.id ORDER BY nftId DESC LIMIT :limit OFFSET :offset");
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        $nfts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $nfts;
    }

    public function getAllNFTsForAUserId($page, $userId)
    {
        $limit = 15;
        $offset = ($page - 1) * $limit;

        // $stmt = $this->db->prepare("SELECT * FROM nfts ORDER BY id DESC LIMIT :limit OFFSET :offset");
        $stmt = $this->db->prepare("SELECT id as nftId, name, image, certificate, description FROM nfts WHERE userid = :userId ORDER BY nftId DESC LIMIT :limit OFFSET :offset");
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        $nfts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $nfts;
    }

    public function addNft($userid, $name, $imgUrl, $certificate, $description)
    {
        // Check if username or email already exists
        $query = "SELECT COUNT(*) as count FROM nfts WHERE certificate = :certificate";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':certificate', $certificate);
        $stmt->execute();

        $result = $stmt->fetch();

        if ($result['count'] > 0) {
            return ['message' => 'Error: Nft with this Certificate already exists', 'code' => 500];
        }

        $query = "INSERT INTO nfts (userid, name, image, certificate, description) VALUES (:userid, :name, :imgUrl, :certificate, :description)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':userid', $userid);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':imgUrl', $imgUrl);
        $stmt->bindParam(':certificate', $certificate);
        $stmt->bindParam(':description', $description);
        $stmt->execute();

        return ['message' => 'New Nft added', 'id' => $this->db->lastInsertId(), 'code' => 200];
    }

    public function editNft($nftId, $name, $description)
    {
        $query = "UPDATE nfts SET name = :name, description = :description WHERE id = :nftId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nftId', $nftId);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->execute();

        return ['message' => 'Nft Succesfully edited', 'code' => 200];
    }

    public function deleteNft($id)
    {
        // Check if username or email already exists
        $query = "DELETE FROM nfts WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return ['message' => 'Nft deleted succesfully'];
    }
}