<?php
    session_start();
    
    if(isset($_POST['email']))
    {
        $isValidated = true;
        
        $login = $_POST['login'];
        if(strlen($login) < 3 || strlen($login) > 20)
        {
            $isValidated = false;
            $_SESSION['errLogin']="Login musi mieć długość 3-20 znaków!";
        }
        
        if(!ctype_alnum($login))
        {
            $isValidated = false;
            $_SESSION['errLogin'] = "Nickname może składać się tylko z liter i cyfr (bez polskich znaków)!";
        }
        $email = $_POST['email'];
        $validatedEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
        
        if(!filter_var($validatedEmail, FILTER_VALIDATE_EMAIL) || ($validatedEmail != $email))
        {
            $isValidated=false;
            $_SESSION['errMail'] = "Podaj poprawny adres e-email!";
        }
        
        $pswd=$_POST['pswd'];
    
        if(strlen($pswd) < 8 || strlen($pswd > 20))
        {
            $isValidated=false;
            $_SESSION['errPswd']="Hasło musi mieć od 8 do 20 znaków!";
        }
        $pswdHashed = password_hash($pswd, PASSWORD_DEFAULT);

        require_once "connect.php";
        
        mysqli_report(MYSQLI_REPORT_STRICT);
        try
        {
            $connect = new mysqli($host, $db_user, $db_pswd, $db_name);
            if($connect -> connect_errno != 0) 
            {
                throw new Exception(mysqli_connect_errno());
            } else
            {
                $result = $connect -> query("SELECT id FROM users WHERE email='$email'");
                if(!$result) throw new Exception($connect -> error); 
                $mailNmbr = $result -> num_rows;
                if($mailNmbr > 0)
                {
                    $isValidated = false;
                    $_SESSION['errMail']="Istnieje już użytkownik z takim adresem e-email";
                }
                $result = $connect -> query("SELECT id FROM users WHERE login = '$login'");
                if(!$result) throw new Exception($connect -> error);
                $nickNmbr = $result -> num_rows;
                if($nickNmbr > 0)
                {
                    $isValidated=false;
                    $_SESSION['errLogin'] = "Użytkownik ".$login." już istnieje. Wybierz inny login.";
                }
                
                if($isValidated)
                {
                    if($connect -> query("INSERT INTO users VALUES (null, '$login', '$pswdHashed', '$email')"))
                    {
                        header('Location: index.php');
                    } else
                    {
                        throw new Exception($connect -> error);
                    }
                }
                $connect->close();
            }
        } catch(Exception $exc)
        {
            echo '<span style="color:red";>Błąd serwera.</span>';
            echo '<br>Informacja developerska '.$exc;
        }
    }



?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zarejestruj się</title>
    <link rel="stylesheet" href="style/register_style.css" type="text/css" />
</head>

<body>
    <div class="form-box">
        <form method="post">
            <h1>Rejestracja</h1>
            <label>Login</label>
            <input type="text" name="login"/><br>
            <?php
                if(isset($_SESSION['errLogin']))
                {
                    echo '<div class="error">'.$_SESSION['errLogin'].'</div>';
                    unset($_SESSION['errLogin']);
                }
            ?>
            <label>E-mail</label>
            <input class="email_field" type="email" name="email">
            <!--<input class="email_field" type="email" name="email" placeholder=" " pattern="[a-zA-Z0-9-]{3,}@[a-zA-Z0-9-]{3,}[.]{1}[a-zA-Z]{2,}" required>-->
            <div class="email_field_state-icon"></div>
            <?php
                if(isset($_SESSION['errMail']))
                {
                    echo '<div class="error">'.$_SESSION['errMail'].'</div>';
                    unset($_SESSION['errMail']);
                }
            ?>
            <label>Hasło</label>
            <input type="password" name="pswd"/><br>
            <?php
                if(isset($_SESSION['errPswd']))
                {
                    echo '<div class="error">'.$_SESSION['errPswd'].'</div>';
                    unset($_SESSION['errPswd']);
                }
            ?>
            <input type="submit" value="Zarejestruj się"/> 
        </form>
    </div>
</body>
</html>