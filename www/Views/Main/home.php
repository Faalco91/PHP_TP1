<nav>
    <ul>
        <li><a href="/">Home</a></li>
        <li><a href="/login">Login</a></li>
        <li><a href="/register">Register</a></li>
    </ul>
</nav>

<?php
session_start();

if (isset($_SESSION['user'])) {
    // Si l'utilisateur est connecté
    echo "<h1>Bienvenue, " . htmlspecialchars($_SESSION['user']['firstname']) . " !</h1>";
    echo '<a href="/logout">Se déconnecter</a>';
} else {
    // Si l'utilisateur n'est pas connecté
    echo '<h1>Bienvenu !</h1>';
    echo '<a href="/login"><button>Se connecter</button></a>';
    echo '<a href="/register"><button>S\'inscrire</button></a>';
}
?>
