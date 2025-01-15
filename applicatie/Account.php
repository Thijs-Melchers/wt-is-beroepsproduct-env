<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="normalise.css">
    <link rel="stylesheet" href="Pizzaria.css">
    <title>Pizza Sole Machina</title>
</head>
<body>
    <header>
        <a id="Logo" href="index.php"><img src="Images/Logo.png" alt=Logo></a>
        <p>Pizza Sole Machina </p>
        <a id="Account" href="Account.php"><img src="Images/Log.in.png" alt=Log.in></a>
    </header>
    <nav>
        <ul>
            <li><a id="Overzicht" href="BestellingAfgerond.php"><img src="Images/Profile.png" alt=Profile></a></li>
            <li><a id="Menu" href="index.php"><img src="Images/Menu.png" alt=Menu></a></li>
            <li><a id="Winkelwagen" href="Winkelwagen.php"><img src="Images/Winkelwagen.webp" alt=Winkelwagen></a></li>
        </ul>
    </nav>
    <section>
  <form action="index.php">
    <h4>Inloggen</h4>
                <label>E-Mailadres: *</label>
                <input type="email" name="E-Mailadres" required placeholder="Vul hier uw E-Mailadres in">
                <label>Wachtwoord: *</label>
                <input type="password" name="Wachtwoord" required placeholder="Vul hier uw Wachtwoord in">
                <input type="submit" value="Inloggen">
        <p>Nog geen account? <a href="Registreren.php">Registreer</a> hier.</p>
        <p>Medewerker? <a href="InloggenMedewerker.php">Klik</a> hier.</p>
            <a href="index.php">Uitloggen</a>
        </form>

    </section>
    <footer>
        <div>
            <p>Telefoonnummer: 0693849241</p>
        </div>
        <div>
        <a href="Info.php"><p>Contact</p></a>
            </div>
        <div>
        <a href="Info.php">Informatie</a>
        </div>
    </footer>   
    </body>
</html>