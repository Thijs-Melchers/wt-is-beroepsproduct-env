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
        SELECT b.id AS bestelling_id, k.naam, b.besteldatum, b.pizza, b.aantal, b.status
        FROM bestellingen b
        JOIN klanten k ON b.klant_id = k.id
        ORDER BY b.besteldatum DESC
    ";
    $stmt = $verbinding->query($bestellingenSql);
    $bestellingen = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Fout bij ophalen bestellingen: " . htmlspecialchars($e->getMessage() ?? 'Onbekende fout'));
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
        die("Fout bij het bijwerken van de status: " . htmlspecialchars($e->getMessage() ?? 'Onbekende fout'));
    }
}

$gegroepeerdeBestellingen = [];
foreach ($bestellingen as $bestelling) {
    $bestellingDatum = (new DateTime($bestelling['besteldatum']))->format('d-m-Y H:i:s');

    if (!isset($gegroepeerdeBestellingen[$bestellingDatum])) {
        $gegroepeerdeBestellingen[$bestellingDatum] = [];
    }

    $bestellingId = $bestelling['bestelling_id'];

    if (!isset($gegroepeerdeBestellingen[$bestellingDatum][$bestellingId])) {
        $gegroepeerdeBestellingen[$bestellingDatum][$bestellingId] = [
            'naam' => $bestelling['naam'] ?? 'Onbekend',
            'pizza' => [],
            'status' => $bestelling['status'] ?? 'Onbekend',
        ];
    }

    $gegroepeerdeBestellingen[$bestellingDatum][$bestellingId]['pizza'][] = [
        'pizza' => $bestelling['pizza'],
        'aantal' => $bestelling['aantal'],
    ];
}
?>

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
        <a id="Logo" href=""><img src="Images/Logo.png" alt="Logo"></a>
        <p>Pizza Sole Machina</p>
        <?php if (isset($_SESSION['username'])): ?>
            <form action="" method="post" style="display: inline;">
                <button type="submit" name="logout" id="UitlogKnop">Uitloggen</button>
            </form>
            <span id="WelkomTekst"><?= htmlspecialchars($_SESSION['username'] ?? 'Onbekend') ?></span>
        <?php else: ?>
            <a href="Account.php"><img src="Images/Log.in.png" alt=""></a>
        <?php endif; ?>
    </header>

    <section>
        <h1>Recente Bestellingen:</h1>
        <?php if (!empty($gegroepeerdeBestellingen)): ?>
            <?php foreach ($gegroepeerdeBestellingen as $besteldatum => $bestellingenGroep): ?>
                <div class="GroepBestelling">
                    <h3>Besteldatum: <?= htmlspecialchars($besteldatum) ?></h3>
                    <?php foreach ($bestellingenGroep as $bestellingId => $bestelling): ?>
                        <div class="Bestelling">
                            <h4>Naam: <?= htmlspecialchars($bestelling['naam']) ?></h4>
                            <ul>
                                <?php foreach ($bestelling['pizza'] as $pizza): ?>
                                    <li><?= htmlspecialchars($pizza['aantal']) ?>x <?= htmlspecialchars($pizza['pizza']) ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <form action="" method="post">
                                <input type="hidden" name="bestelling_id" value="<?= htmlspecialchars($bestellingId) ?>">
                                <label for="status_<?= htmlspecialchars($bestellingId) ?>">Wijzig status:</label>
                                <select name="status" id="status_<?= htmlspecialchars($bestellingId) ?>">
                                    <option value="in behandeling" <?= $bestelling['status'] === 'In behandeling' ? 'selected' : '' ?>>
                                        In behandeling</option>
                                    <option value="onderweg" <?= $bestelling['status'] === 'Onderweg' ? 'selected' : '' ?>>Onderweg
                                    </option>
                                    <option value="afgeleverd" <?= $bestelling['status'] === 'Afgeleverd' ? 'selected' : '' ?>>
                                        Afgeleverd</option>
                                </select>
                                <button type="submit" name="update_status">Update Status</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Er zijn nog geen bestellingen geplaatst.</p>
        <?php endif; ?>
    </section>


</body>

</html>