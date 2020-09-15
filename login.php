<?php
	session_start();
	
	if ((!isset($_POST['login'])) || (!isset($_POST['pswd'])))
	{
		header('Location: index.php');
		exit();
	}

	require_once "connect.php";

	try
	{
        $connection = new mysqli($host, $db_user, $db_pswd, $db_name);
        if($connection->connect_errno!=0)
        {
            throw new Exception(mysqli_connect_errno());
        } else {
        	$login = $_POST['login'];
        	$pswd = $_POST['pswd'];
		
			$login = htmlentities($login, ENT_QUOTES, "UTF-8");
	
			if($result = @$connection -> query(
			sprintf("SELECT * FROM users WHERE login='%s'", mysqli_real_escape_string($connection, $login))))
			{
				$usersNmbr = $result -> num_rows;
				if($usersNmbr > 0)
				{
					$row = $result -> fetch_assoc();
					if(password_verify($pswd, $row['pass']))
					{
						$_SESSION['logged'] = true;
				
						unset($_SESSION['error']);
						$result -> free_result();
                		header('Location: shop.php');
					} else {
						$_SESSION['error'] = '<span style="color:red">Nieprawidłowy login lub hasło!</span>';
                		header('Location: index.php');
					}
				} else {
					$_SESSION['error'] = '<span style="color:red">Nieprawidłowy login lub hasło!</span>';
                	header('Location: index.php');	   
            	}	
        	}
			$connection -> close();
		}
	} catch(Exception $exc)
	{
		echo '<span style="color:red";>Błąd serwera</span>';
		echo '<br>Infromacja developerska '.$exc;
	}
	
?>