<?php
    // bevor !Doctype html wegen der sessionvariable!!
	session_start();

	if(isset(($_POST["submit"])) && isset(($_POST["username"])) && (isset($_POST["password"])) && (isset($_POST["password_repetition"]))) {

		$username = $_POST["username"];
		$password = $_POST["password"];
		$password_repetition = $_POST["password_repetition"];

		if ($password != $password_repetition) {
			$failedPassword = true;
		} 
		else {
            //include once damit nicht Datenbank immer neu geladen wird
			include_once("connection.php");
            //in der Datenbank nach dem Benutzenamen suchen.
			$stmt = $connection->prepare("SELECT * FROM users WHERE username = :username");
            //Variablen binden für das execute später
            $stmt->bindParam(":username", $username);
            //ausführen
			$stmt->execute();
            //speichere das Ergebnis auf einer Variablen. Hier entweder der Benutzer mit dem gesuchten username oder die variable ist leer wenn es keinen Benutzer mit diesem username gibt
			$user = $stmt->fetch();
			// Check is user already exists (fragt ob variable nicht leer)
			if ($user) {
				$failedUser = true;
			} else {
				// Add new user als sicheres Passwort anlegen (passwort wird am Server verschlüsselt)
                //PASSWORT_DEFAULT ist die Verschlüsselungsmethode
				$pw_hash = password_hash($password, PASSWORD_DEFAULT);
                //'admin' wird als fixer String übergeben, ist also keine Variable ind braucht deswegen kein BindParam()
				$statement = $connection->prepare("INSERT INTO users (username, role, pw_hash) VALUES (:username, 'admin', :pw_hash)");
                $statement->bindParam(":username", $username);
                $statement->bindParam(":pw_hash", $pw_hash);
				$statement->execute();
				// Login and redirect
                //Sessionvariable damit überprüft werden kann ob der Benutzer den registerprozess (oder loginprozess) durchlaufen hat
                //die Sessionvariablen werden am Browser gespeichert
				$_SESSION["username"] = $username;
		    	$_SESSION["role"] = "admin";
                //navigieren zur index.php
		    	header("location:index.php");
			}
		}
	}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="style.css">
	<title>Register</title>
</head>
<body>

	<main>
		<h1>Register Page</h1>

		<form action="register.php" method="POST">
			
			<div>
				<label for="username">Register</label>
				<br>
				<input type="text" id="username" name="username">
			</div>

			<div>
				<label for="password">Password</label>
				<br>
				<input type="password" id="password" name="password">
			</div>

			<div>
				<label for="password_repetition">Repeat Password</label>
				<br>
				<input type="password" id="password_repetition" name="password_repetition">
			</div>

			<br>
			<input type="submit" name="submit">

            <!--Abfragen  ob User bereits existiert oder Passwort nicht übereinstimmt. Hier in Forms, damit echo funktioniert -->
			<?php
				if (isset($failedPassword)) {
			        echo "<p>Passwords must be equal!</p>";
			    }
			  	if (isset($failedUser)) {
			        echo "<p>Username is already used! Please choose a different one!</p>";
			    }
    		?>


		</form>
	</main>


</body>
</html>