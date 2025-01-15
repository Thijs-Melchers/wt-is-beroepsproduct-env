<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pizza Sole Machina</title>
    <link rel="stylesheet" href="normalise.css">
    <link rel="stylesheet" href="Pizzaria.css">
</head>
<body>
    <header>
        
        <a href="index.php"><img src="Images/Logo.png" alt=""></a>
        <p>Pizza Sole Machina</p>
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
    <div class="Bestelling">
        <h1>001:</h1>
        <h3>5x Pizza Pepperoni</h3>
        <h3>8x Griekse Pizza</h3>
        <h3>3x Pizza Margherita</h3>
        <h3>17:30 uur</h3>
        <h1>Status</h1>
    <select name="Status" >
        <option> In de oven</option>
        <option>Onderweg</option>
        <option>Word bereid</option>
    </select>
    <input class="Status" type="submit" value="Updaten">
</div>
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