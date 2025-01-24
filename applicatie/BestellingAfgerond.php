<?php
require_once("db_connectie.php");
require_once("Uitloggen.php");
maakVerbinding();
session_start();
Uitloggen();

$verbinding->exec('USE Inloggen');


$bestellingen = [];

try {
    $bestellingenSql = "
        SELECT b.id AS bestelling_id, b.klant_id, k.naam, k.adres, k.telefoon, b.pizza, b.aantal, b.besteldatum, b.status
        FROM bestellingen b
        JOIN klanten k ON b.klant_id = k.id
        ORDER BY b.besteldatum DESC
    ";
    $stmt = $verbinding->query($bestellingenSql);
    $bestellingen = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Fout bij ophalen bestellingen: " . htmlspecialchars($e->getMessage()));
}

if (isset($_POST['update_status'], $_POST['bestelling_id'], $_POST['status'])) {
    $bestellingId = $_POST['bestelling_id'];
    $nieuweStatus = $_POST['status'];

    try {
        $updateStatusSql = "UPDATE bestellingen SET status = ? WHERE id = ?";
        $stmt = $verbinding->prepare($updateStatusSql);
        $stmt->execute([$nieuweStatus, $bestellingId]);

        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } catch (PDOException $e) {
        die("Fout bij het bijwerken van de status: " . htmlspecialchars($e->getMessage()));
    }
}

$gegroepeerdeBestellingen = [];
foreach ($bestellingen as $bestelling) {
    $besteldatum = (new DateTime($bestelling['besteldatum']))->format('Y-m-d H:i:s');
    if (!isset($gegroepeerdeBestellingen[$besteldatum])) {
        $gegroepeerdeBestellingen[$besteldatum] = [];
    }
    $gegroepeerdeBestellingen[$besteldatum][] = $bestelling;
}
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="normalise.css">
    <link rel="stylesheet" href="Pizzaria.css">
    <title>Recente Bestellingen - Pizza Sole Machina</title>
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
        <h1>Recente Bestellingen:</h1>
        <?php if (!empty($gegroepeerdeBestellingen)): ?>
            <?php foreach ($gegroepeerdeBestellingen as $besteldatum => $bestellingenGroep): ?>
                <div class="GroepBestelling">
                    <h3>Besteldatum: <?= htmlspecialchars($besteldatum) ?></h3>
                    <?php foreach ($bestellingenGroep as $bestelling): ?>
                        <div class="Bestelling">
                            <h4>Naam: <?= htmlspecialchars($bestelling['naam']) ?></h4>
                            <p><?= htmlspecialchars($bestelling['aantal']) ?>x <?= htmlspecialchars($bestelling['pizza']) ?></p>
                            <p>Status: <strong><?= htmlspecialchars($bestelling['status']) ?></strong></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Er zijn nog geen bestellingen geplaatst.</p>
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