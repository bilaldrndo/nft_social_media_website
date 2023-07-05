<?php
require_once "BaseDao.php";

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class AdminDao extends BaseDao
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllUsers($page)
    {
        $limit = 25;
        $offset = ($page - 1) * $limit;

        $query = "SELECT u.id, username, email, createdAt, IF (isBlocked, 'true', 'false') isBlocked FROM users AS u 
                  LEFT JOIN blocked AS b ON b.userId = u.id
                  WHERE u.isAdmin = false
                  ORDER BY createdAt DESC
                  LIMIT :limit
                  OFFSET :offset";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function blockUser($userId)
    {
        $query = "INSERT INTO blocked(userId, blockedAt, isBlocked) VALUES (:userId, NOW(), true)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function unblockUser($userId)
    {
        $query = "DELETE FROM blocked WHERE userId = :userId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}