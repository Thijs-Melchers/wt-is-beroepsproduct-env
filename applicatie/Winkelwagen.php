<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['pizza']) && isset($_GET['aantal'])) {
    $pizza = $_GET['pizza'];
    $aantal = $_GET['aantal'];

    if (!isset($_SESSION['winkelwagen'])) {
        $_SESSION['winkelwagen'] = [];
    }

    if (isset($_SESSION['winkelwagen'][$pizza])) {
        $_SESSION['winkelwagen'][$pizza] += $aantal;
    } else {
        $_SESSION['winkelwagen'][$pizza] = $aantal;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['verwijder']) && isset($_POST['pizza'])) {
    $pizza = $_POST['pizza'];
    $aantal_verwijder = $_POST['aantal_verwijder'] ?? 0;

    if (isset($_SESSION['winkelwagen'][$pizza]) && $aantal_verwijder > 0) {
        $_SESSION['winkelwagen'][$pizza] -= $aantal_verwijder;
        if ($_SESSION['winkelwagen'][$pizza] <= 0) {
            unset($_SESSION['winkelwagen'][$pizza]);
        }
    }
}

$winkelwagen = $_SESSION['winkelwagen'] ?? [];
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="normalise.css">
    <link rel="stylesheet" href="Pizzaria.css">
    <title>Winkelwagen - Pizza Sole Machina</title>
</head>

<body>
    <header>
        <a id="Logo" href="index.php"><img src="Images/Logo.png" alt="Logo"></a>
        <p>Pizza Sole Machina</p>
        <?php if (isset($_SESSION['username'])): ?>
            <form action="Account.php" method="post" style="display: inline;">
                <button type="submit" id="UitlogKnop">Uitloggen</button>
            </form>
            <span id="WelkomTekst">Welkom, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
        <?php else: ?>
        <?php endif; ?>
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
            <h1>Uw Winkelwagen:</h1>
        </article>
        <?php if (!empty($winkelwagen)): ?>
            <div class="Bestelling">
                <?php foreach ($winkelwagen as $pizza => $aantal): ?>
                    <div class="Pizza">
                        <h3><?= htmlspecialchars($pizza) ?> - <?= htmlspecialchars($aantal) ?>x</h3>
                        <form action="Winkelwagen.php" method="post" style="display: inline;">
                            <input type="hidden" name="pizza" value="<?= htmlspecialchars($pizza) ?>">
                            <label for="aantal_verwijder">Aantal:</label>
                            <input type="number" name="aantal_verwijder" min="1" max="<?= htmlspecialchars($aantal) ?>"
                                value="1" required>
                            <button type="submit" name="verwijder" value="1">Verwijder</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>

            <form action="Betalen.php" method="post">
                <h2>Voer uw gegevens in om door te gaan naar betalen:</h2>

                <label for="naam">Naam:</label>
                <input type="text" id="naam" name="naam" required placeholder="Uw naam">

                <label for="adres">Adres:</label>
                <input type="text" id="adres" name="adres" required placeholder="Uw adres">

                <label for="telefoonnummer">Telefoonnummer:</label>
                <input type="tel" id="telefoonnummer" name="telefoonnummer" required placeholder="Uw telefoonnummer">

                <button type="submit">Doorgaan naar Betalen</button>
            </form>
        <?php else: ?>
            <p>Uw winkelwagen is leeg.</p>
        <?php endif; ?>
    </section>

    <footer>
        <div>
            <p>Telefoonnummer: 0693849241</p>
        </div>
        <div>
            <a href="Info.php">Contact</a>
        </div>
        <div>
            <a href="Info.php">Informatie</a>
        </div>
    </footer>
</body>

</html>