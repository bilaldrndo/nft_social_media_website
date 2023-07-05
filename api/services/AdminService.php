<?php
require_once __DIR__ . "/../dao/AdminDao.php";

class AdminService
{
    protected $dao;

    public function __construct()
    {
        $this->dao = new AdminDao();
    }

    public function getAllUsers($page)
    {
        return $this->dao->getAllUsers($page);
    }

    public function blockUser($userId)
    {
        return $this->dao->blockUser($userId);
    }

    public function unblockUser($userId)
    {
        return $this->dao->unblockUser($userId);
    }
}
?>