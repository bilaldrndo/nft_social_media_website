<?php

use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;

class UserDao
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

    public function getAllUsers($page)
    {
        $limit = 15;
        $offset = ($page - 1) * $limit;

        $query = "SELECT * FROM users LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getAllUsersForLeaderboard($page)
    {
        $limit = 25;
        $offset = ($page - 1) * $limit;

        $query = "SELECT users.username, users.email, users.id as userId, COUNT(nfts.userId) AS numOfpublishedNfts FROM nfts JOIN users ON nfts.userid=users.id GROUP BY users.id ORDER BY numOfpublishedNfts DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
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

        //MARK: Pomjeriti key u poseban file
        $token = JWT::encode(array($password), '74ynr8y3487ry384r', 'HS256');

        $query = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $token); // Store encoded password in the database
        $stmt->execute();

        return ['message' => 'User signed up successfully', 'userId' => $this->db->lastInsertId(), 'code' => 200];
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

        //MARK: Pomjeriti key u poseban file
        $token = JWT::decode($user['password'], new Key('74ynr8y3487ry384r', 'HS256'));
        $decodedToken = json_decode(json_encode($token), true);
        $passwordDecoded = $decodedToken['0'];

        if ($passwordDecoded === $password) {
            unset($user['password']); // Remove password from the response
            return ['user' => $user, 'message' => 'User signed in successfuly', 'code' => 200];
        } else {
            return ['message' => 'Error: Incorrect password', 'code' => 500];
        }
    }

    public function changeUsername($userId, $newUsername)
    {
        $query = "UPDATE users SET username = :username WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $newUsername);
        $stmt->bindParam(':id', $userId);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }
}