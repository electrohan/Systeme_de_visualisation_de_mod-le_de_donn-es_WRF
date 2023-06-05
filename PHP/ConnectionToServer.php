<?php

$host = 'example.com';
$port = 22;
$username = 'user';
$password = 'password';

// Connexion SSH
$connection = ssh2_connect($host, $port);
if (!$connection) {
    die('Impossible de se connecter au serveur.');
}

// Authentification
if (!ssh2_auth_password($connection, $username, $password)) {
    die('Impossible de s\'authentifier sur le serveur.');
}

// Exécution d'une commande
$command = 'ls /var/www/html';
$stream = ssh2_exec($connection, $command);
stream_set_blocking($stream, true);
$output = stream_get_contents($stream);
fclose($stream);

// Affichage du résultat
echo $output;
