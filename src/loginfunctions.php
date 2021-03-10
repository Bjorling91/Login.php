
<?php

	function isValidUser($username, $password) {



		try {

			$db = dbConnect();
			$sql = "SELECT COUNT(*) AS 'antal' FROM user WHERE username = :username AND password = SHA2(:password, 256)";
			$stmt = $db->prepare($sql);
			$stmt->bindValue(":username", $username);
			$stmt->bindValue(":password", $password);
			$stmt->execute();
			$result = $stmt->fetchColumn();
			$db = null;

			return $result;
		} catch( PDOException $e) {
			throw $e;
		}

	}

	function mySessionDestroy() {

		//$_SESSION = array();
		session_unset();

		if ( ini_get( "session.use_cookies" ) ) {

			$sessionCookieData = session_get_cookie_params();

			$path = $sessionCookieData["path"];
			$domain = $sessionCookieData["domain"];
			$secure = $sessionCookieData["secure"];
			$httponly = $sessionCookieData["httponly"];

			setcookie( session_name(), "", time() - 42000, $path, $domain, $secure, $httponly );
		}

        session_destroy();

	}
