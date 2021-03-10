<?php

	function dbConnect() {
		try {
			$dsn = "mysql:dbname=cars;host=localhost";
			$user = "root";
			$password = "";
			$options = array( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			$db = new PDO($dsn, $user, $password, $options);
			return $db;
		} catch( PDOException $e ) {
			throw $e;
		}
	}
	
	function listCars($db) {
		try {
			$sql = "SELECT id, fabrikat, modell, regnr FROM car ORDER BY id DESC";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$cars = $stmt->fetchAll();
			$stmt = null;
			return $cars;
		} catch( PDOException $e ) {
			throw $e;
		}
		
	}
	
	function editCar($db, $regnr) {
		try {
			$sql = "SELECT fabrikat, modell, regnr FROM car WHERE regnr = :regnr";
			$stmt = $db->prepare($sql);
			$stmt->bindValue(":regnr", $regnr);
			$stmt->execute();
			$car = $stmt->fetch();
			$stmt = null;
			return $car;
		} catch( PDOException $e ) {
			throw $e;
		}
	}
	
	function deleteCar($db, $regnr) {
		try {
			$sql = "DELETE FROM car WHERE regnr = :regnr";
			$stmt = $db->prepare($sql);
			$stmt->bindValue(":regnr", $regnr);
			$stmt->execute();
		} catch( PDOException $e ) {
			throw $e;
		}
	}
	
	function insertCar($db, $fabrikat, $modell, $regnr) {
		try {
			$sql = "INSERT INTO car(fabrikat, modell, regnr) VALUES(:fabrikat, :modell, :regnr)";
			$stmt = $db->prepare($sql);
			$stmt->bindValue(":fabrikat", $fabrikat);
			$stmt->bindValue(":modell", $modell);
			$stmt->bindValue(":regnr", $regnr);
			$stmt->execute();
		} catch( PDOException $e ) {
			throw $e;
		}
	}
	
	function updateCar($db, $fabrikat, $modell, $regnr) {
		try {
			$sql = "UPDATE car set fabrikat = :fabrikat, modell = :modell WHERE regnr = :regnr";
			$stmt = $db->prepare($sql);
			$stmt->bindValue(":fabrikat", $fabrikat);
			$stmt->bindValue(":modell", $modell);
			$stmt->bindValue(":regnr", $regnr);
			$stmt->execute();
		} catch(PDOException $e) { 
			throw $e;
		}
	}
	
	function printCarsAsForms($cars) {
		foreach($cars as $car) {			
		?>
			<div class="borderClass">
				<form action="cars.php" method="post">
					<input type="hidden" name="regnr" value="<?php echo($car["regnr"]); ?>" />
					<p>Fabrikat: <?php echo($car["fabrikat"]); ?></p>
					<p>Modell: <?php echo($car["modell"]); ?></p>
					<p>Regnr: <?php echo($car["regnr"]); ?></p>
					<input type="submit" value="Redigera" name="btnEdit" />
					<input type="submit" value="Tabort" name="btnDelete" />
				</form>
			</div>
		<?php
		}
	}
	
	function saveCar($db, $fabrikat, $modell, $regnr, $hidregnr) {
		
		$fabrikat = trim ( $fabrikat );
		$modell = trim ( $modell );
		$regnr = trim( $regnr );
		$hidregnr = trim ( $hidregnr );
		
		$userMsg = "";
		if( strlen($fabrikat) === 0) {
			$userMsg .= "<p>Ange fabrikat!</p>";
		}
		
		if( strlen($modell) === 0) {
			$userMsg .= "<p>Ange modell!</p>";
		}
		
		if( strlen($regnr) === 0) {
			$userMsg .= "<p>Ange regnr!</p>";
		}
		
		if( strlen($regnr) !== 6 ) {
			$userMsg .= "<p>Ange regnr med sex tecken!</p>";
		} else {
			$letters = substr($regnr, 0, 3);
			if( !ctype_alpha($letters) ) {
				$userMsg .= "<p>Första tre tecknen i regnr skall vara bokstäver!</p>";
			}
			$numbers = substr($regnr, -3);
			if( !ctype_digit($numbers) ) {
				$userMsg .= "<p>Sista tre tecknen i regnr skall vara siffror!</p>";
			}
		}
		
		if( strlen($userMsg) === 0) {
			if(empty($hidregnr)) {
					insertCar($db, $fabrikat, $modell, $regnr);
			} else { 
				updateCar($db, $fabrikat, $modell, $hidregnr);
			}
		} else {
			return $userMsg;
		}
	}
	
	function printCarForm($car = null) {
		?>
		Spara ny bil / redigera befintlig bil:
		<form action="cars.php" method="post">
		
			<input type="text" id="txtFabrikat" name="txtFabrikat" placeholder="Fabrikat" 
				<?php 
					if( $car !== null ) { 
						echo( "value='" . $car["fabrikat"] . "'" ); 
					} 
				?> 
			/>
				
			<input type="text" id="txtModell" name="txtModell" placeholder="Modell" 
				<?php 
					if( $car !== null ) { 
						echo( "value='" . $car["modell"] . "'" ); } 
				?> 
			/>
				
			<input type="text" id="txtRegNr" name="txtRegNr" placeholder="Regnr" 
				<?php 
					if( $car !== null ) { 
						echo( "value='"  . $car["regnr"] . "'" ); 
						echo( " disabled='disabled'" ); } 
				?> 
			/>
				
			<input type="hidden" id="hidRegNr" name="hidRegNr" 
				<?php 
					if( $car !== null ) { 
						echo( "value='" . $car["regnr"] ."'" ); } 
				?> 
			/>
			<br />
			<input type="submit" id="btnSave" name="btnSave" value="Spara" />
			<input type="reset" id="btnReset" name="btnReset" value="Rensa" />
		</form>
		
		<?php
	}
	
	function printCarsAsTable($cars) {
		
		?>
		
		<table>
			<thead>
				<tr>
					<th>regnr</th>
					<th>fabrikat</th>
					<th>modell</th>
				<tr>
			</thead>
			<tbody>
			
		<?php
		foreach($cars as $car) {			
		?>
				<tr>
					<td><?php echo( $car["regnr"] ); ?></td>
					<td><?php echo( $car["fabrikat"] ); ?></td>
					<td><?php echo( $car["modell"] ); ?></td>
				</tr>
			
		<?php
		}
		?>
			</tbody>
		</table>
		<?php
		
	}
	
	function printCarsAsTableWithButtons($cars) {
		
		?>
		
		<table>
			<!--<thead>
				<tr>
					<th>regnr</th>
					<th>fabrikat</th>
					<th>modell</th>
					<th></th>
					<th></th>
				</tr>
			</thead>-->
			<tbody>
			
		<?php
		foreach($cars as $car) {			
		?>	
			<tr>
				
				<td><?php echo( $car["regnr"] ); ?></td>
				<td><?php echo( $car["fabrikat"] ); ?></td>
				<td><?php echo( $car["modell"] ); ?></td>
				
				<td>
					<a href="cars.php?linkDelete=delete&regnr=<?php echo( $car["regnr"] ); ?>" class="btn btn-primary btn-sm" data-delete="<?php echo( $car["regnr"] ); ?>">Delete</a>
				</td>
				<td>
					<a href="cars.php?linkEdit=edit&regnr=<?php echo( $car["regnr"] ); ?>" class="btn btn-primary btn-sm" data-edit="<?php echo( $car["regnr"] ); ?>">Edit</a>
				</td>
		
			</tr>
		
		<?php
		}
		?>
			</tbody>
		</table>
		<?php
	}
	