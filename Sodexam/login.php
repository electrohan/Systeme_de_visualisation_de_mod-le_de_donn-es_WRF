<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/login.css">
    <title>Sodexam login</title>
</head>
<body>
        <form method="post" action="../PHP/Authentification.php" class="login">
            <h3>Connexion</h3>
            <input type="text" name="Matricule" placeholder="Matricule">
            <input type="password" name="code" placeholder="code">
            <button name="valider"> Valider </button>
        </form>
</body>
</html>