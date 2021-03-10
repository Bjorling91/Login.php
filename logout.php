<?php



	include( "src/loginfunctions.php" );

	session_start();

	//Om användare surfar till sidan utan att vara påloggad
	if( !isset( $_SESSION["username"] ) ) {
		mySessionDestroy();
		header( "location: login.php" );
		exit();
	} else {
		mySessionDestroy();
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
			<div id="sitecontent">

				<p>Du är utloggad!
				<br />
				<a href="login.php">Login</a>
				</p>

				<div id="myId">
					Företaget <span>Mina Bilar</span> säljer nya och begagnade bilar.
				</div>
			</div>
			<footer>Mina Bilar kontaktinformation</footer>
		</div>
	</body>
</html>
