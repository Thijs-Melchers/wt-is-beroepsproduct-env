<?php
require_once("db_connectie.php");
require_once("Uitloggen.php");
maakVerbinding();
session_start();
Uitloggen();

$verbinding->exec('USE Inloggen');


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verwijder_pizza'])) {
    $pizza = $_POST['pizza'] ?? '';
    $aantalTeVerwijderen = $_POST['aantal_verwijderen'] ?? 0;

    if (!empty($pizza) && isset($_SESSION['winkelwagen'][$pizza]) && $aantalTeVerwijderen > 0) {
        $huidigAantal = $_SESSION['winkelwagen'][$pizza];

        $nieuwAantal = $huidigAantal - $aantalTeVerwijderen;

        if ($nieuwAantal > 0) {
            $_SESSION['winkelwagen'][$pizza] = $nieuwAantal;
        } else {
            unset($_SESSION['winkelwagen'][$pizza]);
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['naam'], $_POST['adres'], $_POST['telefoonnummer'])) {
    $naam = $_POST['naam'];
    $adres = $_POST['adres'];
    $telefoonnummer = $_POST['telefoonnummer'];
    $winkelwagen = $_SESSION['winkelwagen'] ?? [];

    if (!empty($winkelwagen)) {
        try {
            $klantQuery = "INSERT INTO klanten (naam, adres, telefoon) OUTPUT INSERTED.id VALUES (?, ?, ?)";
            $stmt = $verbinding->prepare($klantQuery);
            $stmt->execute([$naam, $adres, $telefoonnummer]);
            $klantId = $stmt->fetchColumn();

            $bestellingQuery = "INSERT INTO bestellingen (klant_id, pizza, aantal, besteldatum) VALUES (?, ?, ?, GETDATE())";
            $stmt = $verbinding->prepare($bestellingQuery);

            foreach ($winkelwagen as $pizza => $aantal) {
                $stmt->execute([$klantId, $pizza, $aantal]);
            }

            unset($_SESSION['winkelwagen']);
            $bestellingGeslaagd = true;
        } catch (PDOException $e) {
            die("Fout bij opslaan bestelling: " . htmlspecialchars($e->getMessage()));
        }
    }
}
$winkelwagen = $_SESSION['winkelwagen'] ?? [];

$isIngelogd = isset($_SESSION['username']);
$opgeslagenAdres = $isIngelogd ? ($_SESSION['user_address'] ?? '') : '';
$opgeslagenTelefoon = $isIngelogd ? ($_SESSION['user_phone'] ?? '') : '';
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
            <form action="" method="post" style="display: inline;">
                <button type="submit" name="logout" id="UitlogKnop">Uitloggen</button>
            </form>
            <span id="WelkomTekst"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
        <?php else: ?>
            <a href="Account.php"><img src="Images/Log.in.png" alt=""></a>
        <?php endif; ?>
    </header>

    <nav>
        <ul>
            <li><a id="RecenteBestellingen" href="BestellingAfgerond.php"><img src="Images/Profile.png" alt=""></a></li>
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

                            <label for="aantal_verwijderen">Aantal te verwijderen:</label>
                            <input type="number" id="aantal_verwijderen" name="aantal_verwijderen" value="1" min="1"
                                max="<?= htmlspecialchars($aantal) ?>" required><br><br>

                            <button type="submit" name="verwijder_pizza">Verwijder</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>

            <form action="Winkelwagen.php" method="post">
                <h2>Voer uw gegevens in om door te gaan naar bestellen:</h2>

                <?php if (!$isIngelogd || empty($opgeslagenAdres)): ?>
                    <label for="naam">Naam:</label>
                    <input type="text" id="naam" name="naam" required placeholder="Uw naam">

                    <label for="adres">Adres:</label>
                    <input type="text" id="adres" name="adres" required placeholder="Uw adres">

                    <label for="telefoonnummer">Telefoonnummer:</label>
                    <input type="tel" id="telefoonnummer" name="telefoonnummer" required placeholder="Uw telefoonnummer">
                <?php else: ?>
                    <p>Naam: <strong><?= htmlspecialchars($_SESSION['username']) ?></strong></p>
                    <p>Adres: <strong><?= htmlspecialchars($opgeslagenAdres) ?></strong></p>
                    <p>Telefoonnummer: <strong><?= htmlspecialchars($opgeslagenTelefoon) ?></strong></p>
                    <input type="hidden" name="naam" value="<?= htmlspecialchars($_SESSION['username']) ?>">
                    <input type="hidden" name="adres" value="<?= htmlspecialchars($opgeslagenAdres) ?>">
                    <input type="hidden" name="telefoonnummer" value="<?= htmlspecialchars($opgeslagenTelefoon) ?>">
                <?php endif; ?>

                <button type="submit">Bestellen</button>
            </form>
            <?php if (isset($bestellingGeslaagd) && $bestellingGeslaagd): ?>
                <p>Uw bestelling is succesvol geplaatst!</p>
            <?php endif; ?>
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