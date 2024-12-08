<?php
namespace App\Controllers;

use App\Core\User as U;
use App\Core\View;

class User
{
    public function register(): void
    {
        $view = new View("User/register.php", "front.php");

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Cette partie sert à récupérer les données du formulaire
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $passwordConfirm = $_POST['password_confirm'];
            $firstname = trim($_POST['firstname']);
            $lastname = trim($_POST['lastname']);
            $country = trim($_POST['country']);
            
            // On créer un tableau pour stocker les erreurs
            $errors = [];
    
            // Ce qui va permettre la validation des champs
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "L'email est invalide.";
            }
            
            if (empty($password) || strlen($password) < 6) {
                $errors[] = "Votre mot de passe doit contenir au moins 6 caractères.";
            }
    
            if ($password !== $passwordConfirm) {
                $errors[] = "Les mots de passe ne correspondent pas.";
            }
    
            if (empty($firstname)) {
                $errors[] = "Le prénom est obligatoire.";
            }
    
            if (empty($lastname)) {
                $errors[] = "Le nom est obligatoire.";
            }
    
            if (empty($country)) {
                $errors[] = "Le pays est obligatoire.";
            }
    
            // On vérifie si l'email existe déjà dans la base de données
            $db = new \PDO('sqlite:' . __DIR__ . '/../db/database.sqlite');
            $stmt = $db->prepare("SELECT id FROM user WHERE email = :email");
            $stmt->bindParam(':email', $email, \PDO::PARAM_STR);
            $stmt->execute();
            if ($stmt->fetch()) {
                $errors[] = "L'email est déjà utilisé.";
            }
    
            // Et Si il y a des erreurs, on retourne à la page d'inscription
            if (!empty($errors)) {
                header("Location: /register");
                return;
            }
    
            // Si tout est ok, on hashe le mot de passe et on insère les données dans la base
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("INSERT INTO user (email, password, firstname, lastname, country) VALUES (:email, :password, :firstname, :lastname, :country)");
            $stmt->bindParam(':email', $email, \PDO::PARAM_STR);
            $stmt->bindParam(':password', $hashedPassword, \PDO::PARAM_STR);
            $stmt->bindParam(':firstname', $firstname, \PDO::PARAM_STR);
            $stmt->bindParam(':lastname', $lastname, \PDO::PARAM_STR);
            $stmt->bindParam(':country', $country, \PDO::PARAM_STR);
            $stmt->execute();
    
            // Redirection vers la page de connexion après l'inscription
            header("Location: /login");
            exit;
        }

    }
    

    public function login(): void
    {
        $view = new View("User/login", "front.php");
        //echo $view;
    }


    public function logout(): void
    {
        $user = new U;
        $user->logout();
        //header("Location: /");
    }



}

