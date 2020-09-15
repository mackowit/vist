<?php
	session_start();
	
	if ((isset($_SESSION['logged'])) && ($_SESSION['logged']==true))
	{
		header('Location: shop.php');
		exit();
	}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grupa Vist - zaloguj się</title>
    <link rel="stylesheet" href="style/index_style.css" type="text/css" />
</head>
<body>
<div class="form-box">
    <h1>Zaloguj się</h1>
    <form action="login.php" method="post">
        <label>Login</label>
        <input type="text" name="login"/>
        <label>Hasło</label>
        <input type="password" name="pswd"/>
        <input type="submit" value="Zaloguj się"/>
    </form>
    <?php
        if(isset($_SESSION['error']))	echo $_SESSION['error'];
    ?>
    <p>Nie masz konta? <a href="register.php">Zarejestruj się</a></p>
    <img src="images/grupa-vist-logo.svg">
</div>
</body>
</html>