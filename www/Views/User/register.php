<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
</head>
<body>
<nav>
    <ul>
        <li><a href="/">Home</a></li>
        <li><a href="/login">Login</a></li>
        <li><a href="/register">Register</a></li>
    </ul>
</nav>

    <h1>Créer un compte</h1>
    <form action="/register" method="POST">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br>

        <label for="password">Mot de passe:</label>
        <input type="password" id="password" name="password" required><br>

        <label for="password_confirm">Confirmer le mot de passe:</label>
        <input type="password" id="password_confirm" name="password_confirm" required><br>

        <label for="firstname">Prénom:</label>
        <input type="text" id="firstname" name="firstname" required><br>

        <label for="lastname">Nom:</label>
        <input type="text" id="lastname" name="lastname" required><br>

        <label for="country">Pays:</label>
        <input type="text" id="country" name="country" required><br>

        <input type="submit" value="S'inscrire">
    </form>

    <?php
    // Afficher les erreurs si présents
    if (isset($errors)) {
        foreach ($errors as $error) {
            echo "<p style='color: red;'>$error</p>";
        }
    }
    ?>



</body>
</html>
