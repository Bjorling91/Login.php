<?php

//Användarnamn: test
//Lösenord: test 

	include("src/loginfunctions.php");
	include("src/carfunctions.php");

	try {
		session_start();
		//Om användaren redan är påloggad se till att redirecta till cars.php
		if( isset ( $_SESSION["username"] ) ) {
			header( "location: cars.php" );
			exit();
		}

		//Om det är första gången användaren kommer till sidan.
		if( !isset( $_SESSION["username"] ) && !isset($_POST["btnSend"] ) ) {
			mySessionDestroy();
		}

		//Har användaren klickat på btnSend
		if( isset( $_POST["btnSend"] ) ) {

			//Kontrollera om det är en giltig användare eller inte
			$isUser = isValidUser( $_POST["txtUserName"], $_POST["txtPassWord"] );

			if( !$isUser ) {
				//Inte giltig användare
				mySessionDestroy();
				$errorMsg = "Felaktigt användarnamn och/eller lösenord";
			} else {

				//Giltig användare
				$_SESSION["username"] = $_POST["txtUserName"];
				$_SESSION["logindatetime"] = date( "Ymd H:i:s" );
				$_SESSION["lastservercall"] = date( "Ymd H:i:s" );

				header( "location: cars.php" );
				exit();
			}
		}
	} catch(PDOException $e) {
		$errorMsg = $e->getMessage();
	}


?>
<!doctype html>
<html lang="sv">
	<head>
        <meta charset="utf-8" />
        <title>
			En komplett HTML5 sida med PHP inslag!
        </title>

			<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

			<link rel="stylesheet" href="style/cars.css"  type="text/css" />
	</head>
	<body>

		<div id="sitewrapper">

			<header>
				<h1>Mina Bilar</h1>
			</header>
			<p><?php if( isset( $errorMsg ) ) { echo( $errorMsg ); } ?></p>
			<div id="sitecontent">

				<form action="login.php" method="post">
					Användarnamn:<input type="text" name="txtUserName" />
					<br />
					Lösenord:<input type="password" name="txtPassWord" />
					<br />
					<input type="submit" name="btnSend" value="Login" />
					<input type="reset" value="Reset" />
				</form>

				<div id="myId">
					Företaget <span>Mina Bilar</span> säljer nya och begagnade bilar.
				</div>
			</div>
			<footer>Mina Bilar kontaktinformation</footer>
		</div>
	</body>
</html>
