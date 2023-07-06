<?php
require_once __DIR__ . "/../dao/UserDao.php";

class UserService
{
    protected $dao;

    public function __construct()
    {
        $this->dao = new UserDao();
    }

    public function getAllUsersForLeaderboard($page)
    {
        return $this->dao->getAllUsersForLeaderboard($page);
    }

    public function signUpUser($username, $email, $password)
    {
        return $this->dao->signUpUser($username, $email, $password);
    }

    public function signInUser($email, $password)
    {
        return $this->dao->signInUser($email, $password);
    }

    public function signOutUser($userId)
    {
        return $this->dao->addNewItemToAuthHistory($userId, 'signout');
    }
}
?>