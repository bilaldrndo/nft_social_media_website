<?php
require_once "BaseDao.php";

use \Firebase\JWT\JWT;

class UserDao extends BaseDao
{

    public function __construct()
    {
        parent::__construct();
    }

    public function addNewItemToAuthHistory($userId, $type)
    {
        $query = "INSERT INTO auth_history (userId, time, type) VALUES (:userId, NOW(), :type)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':type', $type);
        $stmt->execute();
    }

    public function signUpUser($username, $email, $password)
    {
        // Check if username or email already exists
        $query = "SELECT COUNT(*) as count FROM users WHERE username = :username OR email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $result = $stmt->fetch();

        if ($result['count'] > 0) {
            return ['message' => 'Error: Username or email already exists', 'code' => 500];
        }

        $passwordHashed = md5($password);

        $query = "INSERT INTO users (username, email, password, createdAt, isAdmin) VALUES (:username, :email, :password, NOW(), false)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $passwordHashed); // Store encoded password in the database
        $stmt->execute();

        $token = JWT::encode(array($email), '74ynr8y3487ry384r', 'HS256');

        $userId = $this->db->lastInsertId();

        $this->addNewItemToAuthHistory($userId, 'signup');

        return ['message' => 'User signed up successfully', 'token' => $token, 'userId' => $userId, 'code' => 200];
    }

    public function signInUser($email, $password)
    {
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $user = $stmt->fetch();

        if (!$user) {
            return ['message' => 'Error: User does not exist', 'code' => 500];
        }

        $userId = $user['id'];

        //check if user is blcoked
        $query2 = "SELECT COUNT(*) as count FROM blocked WHERE userId = :id";
        $stmt2 = $this->db->prepare($query2);
        $stmt2->bindParam(':id', $userId);
        $stmt2->execute();

        $blockStatus = $stmt2->fetch();

        if ($blockStatus['count'] > 0) {
            return ['message' => 'Error: User is blocked', 'code' => 500];
        }

        $passwordHashed = md5($password);

        $token = JWT::encode(array($email), '74ynr8y3487ry384r', 'HS256');

        if ($user['password'] === $passwordHashed) {
            unset($user['password']); // Remove password from the response

            $this->addNewItemToAuthHistory($userId, 'signin');
            return ['user' => $user, 'message' => 'User signed in successfuly', 'token' => $token, 'code' => 200];
        } else {
            return ['message' => 'Error: Incorrect password', 'code' => 500];
        }
    }

    public function getAllUsersForLeaderboard($page)
    {
        $limit = 25;
        $offset = ($page - 1) * $limit;

        $query = "SELECT users.username, users.email, users.id as userId, COUNT(nfts.userId) AS numOfpublishedNfts FROM nfts 
                  JOIN users ON nfts.userid=users.id 
                  GROUP BY users.id 
                  ORDER BY numOfpublishedNfts DESC
                  LIMIT :limit
                  OFFSET :offset";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}