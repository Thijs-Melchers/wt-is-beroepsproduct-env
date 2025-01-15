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
        <a id="Logo" href="index.php"><img src="Images/Logo.png" alt=""></a>
        <p>Pizza Sole Machina </p>
        <a id="Account" href="Account.php"><img src="Images/Log.in.png" alt=""></a>
    </header>
    <nav>
        <ul>
            <li><a id="Overzicht" href="BestellingAfgerond.php"><img src="Images/Profile.png" alt=""></a></li>
            <li><a id="Menu" href="index.php"><img src="Images/Menu.png" alt=""></a></li>
            <li><a id="Winkelwagen" href="Winkelwagen.php"><img src="Images/Winkelwagen.webp" alt=""></a></li>
        </ul>
    </nav>
    <section>
        <article>
            <h4>Inloggen als Medewerker</h4>
            <form action="Besteloverzicht.php">
                <label>E-Mailadres</label>
                <input type="email" name="E-Mailadres"  required placeholder="Vul hier uw E-Mailadres in">
                <label>Wachtwoord:</label>
                <input type="password" name="Wachtwoord"  required placeholder="Vul hier uw Wachtwoord in">
                
                <input type="submit" value="Inloggen">
            </form>
        </article>
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