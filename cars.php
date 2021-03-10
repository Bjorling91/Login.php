<?php



	include( "src/loginfunctions.php" );

	session_start();

	if( !isset( $_SESSION["username"] ) ) {
		mySessionDestroy();
		header( "location: login.php" );
		exit();
	}


	$bootStrap = false;
	$myOwnCSS = false;
	if( isset( $_SESSION["bootstrap"] ) ) {
		$bootStrap = true;
	}

	if( isset( $_SESSION["myowncss"] ) ) {
		$myOwnCSS = true;
	}

	if( isset ( $_POST["btnCSS"] ) ) {

		if( isset( $_POST["chkBootStrap"] ) ) {
			//setcookie("bootstrap", "bootstrap", time() + 3600);
			$_SESSION["bootstrap"] = true;
			$bootStrap = true;
		} else {
			//setcookie("bootstrap", "", time() - 3600);
			unset( $_SESSION["bootstrap"] );
			$bootStrap = false;
		}

		if( isset( $_POST["chkMyOwnCSS"] ) ) {
			//setcookie("myowncss", "myowncss", time() + 3600);
			$_SESSION["myowncss"] = true;
			$myOwnCSS = true;
		} else {
			//setcookie("myowncss", "", time() - 3600);
			unset( $_SESSION["myowncss"] );
			$myOwnCSS = false;
		}

	}

?>
<!doctype html>
<html lang="sv">
	<head>
        <meta charset="utf-8" />
        <title>
			En komplett HTML5 sida med PHP inslag!
        </title>

		<?php

			if( $bootStrap ) {
				?>
				<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
			<?php
			}

			if ( $myOwnCSS ) {
				?>
				<link rel="stylesheet" href="style/cars.css"  type="text/css" />
			<?php
			}
			?>
	</head>
	<body>

		<div id="sitewrapper">

			<header>
				<h1>Mina Bilar</h1>
			</header>

			<div id="sitecontent">
				<?php

					include( "src/carfunctions.php");

					if( isset( $_SESSION["username"] ) ) {
					echo("<p>Välkommen: <b>" . $_SESSION["username"]
						. "</b> du loggade in <b>"
						. $_SESSION["logindatetime"] . "</b>"
						. " senaste serveranropet genomfördes <b>" . $_SESSION["lastservercall"] . "</b>!</p>");
					echo("<p><a href='logout.php'>Logout</a></p>");
					}
					try {
						$db = dbConnect();

						if(isset( $_POST["btnSave"])) {

							if( isset( $_POST["txtRegNr"] ) ) {
								$regnr = $_POST["txtRegNr"];
							} else {
								$regnr = $_POST["hidRegNr"];
							}

							$userMsg = saveCar( $db, $_POST["txtFabrikat"], $_POST["txtModell"], $regnr , $_POST["hidRegNr"] );
						}

						$car = null;
						if( isset( $_GET["linkEdit"] ) ) {
							$car = editCar( $db, $_GET["regnr"] );
						}

						if( isset( $_GET["linkDelete"] ) ) {
							deleteCar( $db, $_GET["regnr"] );
						}


				?>
				<p>Antal bilar i lager är idag <span>
					<?php
						$cars = listCars($db);
						echo( count( $cars ) );
					?></span>.</p>
				<div>
					<span id="userMsg"><?php
						if( isset( $userMsg ) ) {
							echo( $userMsg );
						}
					?></span>
					<?php
						printCarForm($car);
					?>
				</div>
				<?php
					//printCarsAsForms($cars);
					printCarsAsTableWithButtons( $cars );
					$db = null;
				} catch( PDOException $e ) {
					echo( "<p>" . $e->getMessage() . "</p>" );
				}
				?>

				<a href="logout.php">Logout</a>

				<form action="<?php echo( $_SERVER["PHP_SELF"] ); ?>" method="post">
					Bootstrap: <input type="checkbox"
					<?php if( $bootStrap ) { echo( "checked" ); } ?>
					name="chkBootStrap" />

					MyOwnCSS: <input type="checkbox" name="chkMyOwnCSS"
					<?php if( $myOwnCSS ) { echo( "checked" ); } ?>
					/>
					<input type="submit" value="Add/Remove CSS" name="btnCSS" />
				</form>
				<div id="myId">
					Företaget <span>Mina Bilar</span> säljer nya och begagnade bilar.
				</div>
			</div>
			<footer>Mina Bilar kontaktinformation</footer>
		</div>

		<script src="script/cars.js"></script>
	</body>
</html>
