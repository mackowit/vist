<?php
	session_start();
	
	if (!isset($_SESSION['logged']))
	{
		header('Location: index.php');
		exit();
	}	

	if(isset($_POST['submit']))
	{
		require("PHPMailer/src/PHPMailer.php");
		require("PHPMailer/src/SMTP.php");
		require("PHPMailer/src/Exception.php");
		
		$mail = new PHPMailer\PHPMailer\PHPMailer();
		                      
		$mail->isSMTP();
		$mail->SMTPDebug = 2;
		$mail->Host = 'smtp.mydomain.com';
		$mail->Port = 25;
		$mail->SMTPAuth = true;
		$mail->Username = "postmaster@mydomain.com";
		$mail->Password = "4a>P1NJ";
		$mail->setFrom("postmaster@mydomain.com") ;
		$mail->addAddress('mackowit@o2.pl');
		$mail->isHTML(true);                                  
		$mail->Subject = 'Subject';
		$mail->Body    = '<p>Body</p>';
		$mail->AltBody = 'Body';
		
		if(!$mail->Send()) {
			$_SESSION['info'] = 'Błąd wysyłania e-maila';
		} else {
			$_SESSION['info'] = 'Wysłano e-mail';
		}
}
 /* if(isset($_POST['submit_base'])) {
	  $favs = $_GET['favs'];
  }*/
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grupa Vist - PunkAPI Shop</title>
	<link rel="stylesheet" href="style/shop_style.css"/>
	<link href="css/fontello.css" rel="stylesheet" type="text/css" />
</head>
<body>
	<a href="logout.php">Wyloguj się!</a>
	
	<p class="favorites_counter">Ulubione piwa (<span></span>)</p>
	<form method="post">
		<label for="submit">Wyślij listę ulubionych na maila</label>	
		<input type="submit" name="submit" value="Wyślij">	
		<?php
        	if(isset($_SESSION['info']))
			{
				echo '<span>'.$_SESSION['info'].'</span>';
				unset($_SESSION['info']);
			}
    	?>
	</form>
	<form method="post">
		<label for="submit">Zapisz ulubione na swoim koncie</label>	
		<input type="submit" name="submit_base" value="Zapisz">	
	</form>
	<header>
		<h1>Sklep Vist</h1>
	</header>
	<section>
		<div class="container">
			<div class="item">
				<i class="icon-star-empty"></i>
				<h2></h2>
				<p class="desc"></p>
				<p class="ingr"></p>
				<div class="item_image">
					<img src="" alt="item image">
				</div>
				
			</div>
			<div class="item">
				<i class="icon-star-empty"></i>
				<h2></h2>
				<p class="desc"></p>
				<p class="ingr"></p>
				<div class="item_image">
					<img src="" alt="item image">
				</div>
			</div>
			<div class="item">
				<i class="icon-star-empty"></i>
				<h2></h2>
				<p class="desc"></p>
				<p class="ingr"></p>
				<div class="item_image">
					<img src="" alt="item image">
				</div>
			</div>
			<div class="item">
				<i class="icon-star-empty"></i>
				<h2></h2>
				<p class="desc"></p>
				<p class="ingr"></p>
				<div class="item_image">
					<img src="" alt="item image">
				</div>
			</div>
			<div class="item">
				<i class="icon-star-empty"></i>
				<h2></h2>
				<p class="desc"></p>
				<p class="ingr"></p>
				<div class="item_image">
					<img src="" alt="item image">
				</div>
			</div>
		</div>
	</section>
	<section>
		<div class="counter_container">
			<div class="front_arrow">Last</div>
			<div class="front_shifter">...</div>
			<div class="counter">1</div>
			<div class="counter">2</div>
			<div class="counter">3</div>
			<div class="rear_shifter">...</div>
			<div class="rear_arrow">Next</div>
		</div>
	</section>
	<footer>
	<div class="image">
	<img class="footer_img" src="images/grupa-vist-logo.svg" alt="">
	</div>
	</footer>
    
	<script src="script/main.js"></script>
</body>
</html>