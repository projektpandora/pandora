<?php
    //wichtig ist vor !DOCTYPE html schreiben
	session_start();
    //überprüfen ob sessionvariable gesetzt. dh. ob login oder register prozess gemacht wurde
    if(!isset($_SESSION["username"])) {
        //umleiten zu login
        header("location:login.php");
        //exit wichtig! falls umleiten etwas Zeit benötigt(laden der login seite) würde der User des index.php in dieser zeit sehen. das muss verhindert werden
        exit();
    }


    //bei logout session zerstören
	if(isset($_POST['submit'])) {
        //zerstört eine session, variablen bleiben aber gespeichert. falls also session_start() wieder gemacht wird, gibt es eventuell bereits Variablen
	    session_destroy();
        //Variablen der Sessionvariable löschen
	    unset($_SESSION['username']);
        //zu login zurückleiten
	    header('location:login.php');
	}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="style.css">
	<title>Secret Page</title>
</head>
<body>
	<main>	
		<h1>Login successful!</h1>
		<?php echo "<h2>Welcome " . $_SESSION["username"]  . "!</h2>"?>
		<p>This is super secret!</p>

		<form action="index.php" method="POST">
			<input type="submit" name="submit" value="Logout">
		</form>
	</main>
</body>
</html>