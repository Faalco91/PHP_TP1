<?php
namespace App\Controllers;

use App\Core\User as U;
use App\Core\View;

class User
{
    public function register(): void {
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
    
            // Et Si il y a des erreurs, on reste sur la page d'inscription
            if (!empty($errors)) {
                include_once __DIR__ . '/../Views/User/register.php';
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
    
        // Si la méthode n'est pas POST, afficher le formulaire d'inscription sans erreurs
        include_once __DIR__ . '/../Views/User/register.php';
    }
    
    

    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupération des données du formulaire
            $email = trim($_POST['email']);
            $password = $_POST['password'];
    
            // Tableau pour les erreurs
            $errors = [];
    
            // Validation des champs
            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "L'email est invalide.";
            }
    
            if (empty($password)) {
                $errors[] = "Le mot de passe est requis.";
            }
    
            // Vérification des identifiants si pas d'erreurs
            if (empty($errors)) {
                $db = new \PDO('sqlite:' . __DIR__ . '/../db/database.sqlite');
                $stmt = $db->prepare("SELECT * FROM user WHERE email = :email");
                $stmt->bindParam(':email', $email, \PDO::PARAM_STR);
                $stmt->execute();
                $user = $stmt->fetch(\PDO::FETCH_ASSOC);
    
                if ($user && password_verify($password, $user['password'])) {
                    // Stockage des informations utilisateur dans la session
                    session_start();
                    $_SESSION['user'] = [
                        'id' => $user['id'],
                        'firstname' => $user['firstname']
                    ];
    
                    // Redirection vers la page d'accueil
                    header("Location: /");
                    exit;
                } else {
                    $errors[] = "Email ou mot de passe incorrect.";
                }
            }
    
            // Affiche à nouveau la page login avec les erreurs
            include_once __DIR__ . '/../Views/User/login.php';
            return;
        }
    
        // Affiche la vue de connexion
        include_once __DIR__ . '/../Views/User/login.php';
    }
    
    public function logout(): void
    {
        session_start();
        session_destroy();
        header("Location: /login");
        exit;
    }
    



}

