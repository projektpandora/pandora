<?php
	session_start();

	if(isset($_POST["submit"]) && isset($_POST["username"]) && isset($_POST["password"])) {

        //Damit nur einmal eingebunden wird
		include_once "connection.php";

	    $username = $_POST["username"];
	    $password = $_POST["password"];

	    // Try to find the user
	    $stmt = $connection->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->BindParam(":username",$username);
	    $stmt->execute();
        //Daten des Users speichen falls gefunden
	    $user = $stmt->fetch();
	  	
	    if ($user) {
            //überprüfen ob passwort stimmt schwierig weil verschlüsselt gespeichert. Dazu gibt es passwort_verify()
            // Da es nur einen User gibt funktioniert $user["pw_hash"] ansonsten würde es ja mehrere geben
	    	if(password_verify($password, $user["pw_hash"])) {
                //sessionvariablen anlegen, damit der Benutzer weitergeleitet werden kann
		    	$_SESSION["username"] = $user["username"];
		    	$_SESSION["role"] = $user["role"];
                //weiterleiten zu index.php
		    	header("location:index.php");
		    } else {
		    	$failed = true;
		    }
	    } else {
	    	$failed = true;
	    }
	}
?>


<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="style.css">
	<title>Login</title>
</head>
<body>
	<main>
		<h1>Login Page</h1>

		<form action="login.php" method="POST">
			<div>
				<label for="username">Username</label>
				<br>
				<input type="text" id="username" name="username">
			</div>

			<div>
				<label for="password">Password</label>
				<br>
				<input type="text" id="password" name="password">
			</div>

			<br>
			<input type="submit" name="submit" value="Login">
		</form>
		<p>New User? <a href="register.php">Register first</a></p>

        <!-- Info falls Login fehlschlägt   -->
		<?php
			if (isset($failed)) {
		        echo "<p>Login failed! Username or passwort is wrong!</p>";
		    }
	    ?>
    </main>

</body>
</html>