<?php
require_once '../Config/databse.php';

class UserModel {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    public function isEmailUnique($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM user WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->rowCount() === 0;
    }

    public function createUser($email, $password, $firstname, $lastname, $country) {
        $stmt = $this->pdo->prepare("INSERT INTO user (email, password, firstname, lastname, country) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$email, $password, $firstname, $lastname, $country]);
    }

    public function getUserByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM user WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
