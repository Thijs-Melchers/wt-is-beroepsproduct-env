<?php
require_once("db_connectie.php");
require_once("Uitloggen.php");
maakVerbinding();
session_start();
Uitloggen();

$verbinding->exec('USE Inloggen');

if (!empty($username) && !empty($password) && !empty($role)) {
    try {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (username, password, role) VALUES (:username, :password, :role)";
        $stmt = $verbinding->prepare($sql);

        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':role', $role);

        if ($stmt->execute()) {
            $lastUserId = $verbinding->lastInsertId();

            $sql = "SELECT id, username, role FROM users WHERE id = :id";
            $stmt = $verbinding->prepare($sql);
            $stmt->bindParam(':id', $lastUserId);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                if ($user['role'] == 'medewerker') {
                    header("Location: besteloverzicht.php");
                } else {
                    header("Location: Index.php");
                }
                exit;
            }
        } else {
            $error = "Er is een fout opgetreden bij de registratie.";
        }
    } catch (PDOException $e) {
        $error = "Fout bij invoegen: " . $e->getMessage();
    }
} else {
    $error = "Vul alle velden in!";
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
    <title>Pizza Sole Machina</title>
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
            <li><a id="Overzicht" href="BestellingAfgerond.php"><img src="Images/Profile.png" alt=""></a></li>
            <li><a id="Menu" href="index.php"><img src="Images/Menu.png" alt=""></a></li>
            <li><a id="Winkelwagen" href="Winkelwagen.php"><img src="Images/Winkelwagen.webp" alt=""></a></li>
        </ul>
    </nav>

    <section>
        <div class="Info">
            <h1>Privacyverklaring</h1>
            <h4>Deze privacyverklaring beschrijft hoe wij persoonlijke gegevens verzamelen, gebruiken en beschermen in
                overeenstemming met de toepasselijke wetgeving, inclusief de Algemene Verordening Gegevensbescherming
                (AVG). Het doel van deze verklaring is om transparantie te bieden over hoe uw persoonlijke gegevens
                worden behandeld.</h4>

            <h5>1. Verzameling van gegevens</h5>
            <h4>Wij verzamelen en verwerken persoonlijke gegevens die u aan ons verstrekt, bijvoorbeeld wanneer u onze
                website bezoekt, contact met ons opneemt, of gebruik maakt van onze diensten. Dit kan onder andere de
                volgende gegevens omvatten:</h4>

            <h4>Naam</h4>
            <h4>E-mailadres</h4>
            <h4>Telefoonnummer</h4>
            <h4>Adres</h4>
            <h4>Betalingsinformatie</h4>
            <h4>IP-adres</h4>
            <h5>2. Doelen van gegevensverwerking</h5>
            <h4>Wij verwerken uw gegevens voor de volgende doeleinden:</h4>

            <h4>Het leveren van diensten en producten die u hebt aangevraagd</h4>
            <h4>Het verbeteren van onze website en diensten</h4>
            <h4>Klantondersteuning en communicatie</h4>
            <h4>Het verzenden van marketingcommunicatie (indien u hiervoor toestemming hebt gegeven)</h4>
            <h4>Het naleven van wettelijke verplichtingen</h4>
            <h5>3. Rechtsgrondslag voor verwerking</h5>
            <h4>Wij verwerken uw gegevens op basis van de volgende rechtsgrondslagen:</h4>

            <h4>Uw toestemming</h4>
            <h4>De uitvoering van een overeenkomst</h4>
            <h4>De naleving van een wettelijke verplichting</h4>
            <h4>Het gerechtvaardigd belang van onze organisatie (bijvoorbeeld voor marketingdoeleinden)</h4>
            <h5>4. Delen van gegevens</h5>
            <h4>Wij delen uw persoonlijke gegevens alleen met derden wanneer dit noodzakelijk is voor de uitvoering van
                onze diensten, of wanneer wij hiertoe wettelijk verplicht zijn. Derden kunnen bijvoorbeeld zijn:</h4>

            <h4>Externe dienstverleners die ons helpen bij het uitvoeren van diensten</h4>
            <h4>Overheidsinstanties in geval van wettelijke verplichtingen</h4>
            <h4>Wij zorgen ervoor dat deze derden uw gegevens op een veilige en verantwoorde manier behandelen.</h4>

            <h5>5. Bewaring van gegevens</h5>
            <h4>Wij bewaren uw gegevens zolang als nodig is voor de doeleinden waarvoor ze zijn verzameld, tenzij een
                langere bewaartermijn vereist is op grond van wet- en regelgeving.</h4>

            <h5>6. Beveiliging van gegevens</h5>
            <h4>Wij nemen passende technische en organisatorische maatregelen om uw persoonsgegevens te beschermen tegen
                verlies, misbruik, ongeautoriseerde toegang, openbaarmaking, wijziging of vernietiging.</h4>

            <h5>7. Uw rechten</h5>
            <h4>U hebt verschillende rechten met betrekking tot de verwerking van uw persoonsgegevens, waaronder:</h4>

            <h4>Het recht om toegang te vragen tot de gegevens die wij over u verwerken</h4>
            <h4>Het recht om uw gegevens te laten corrigeren of verwijderen</h4>
            <h4>Het recht om bezwaar te maken tegen de verwerking van uw gegevens</h4>
            <h4>Het recht om uw toestemming in te trekken (indien de verwerking is gebaseerd op toestemming)</h4>
            <h4>Als u gebruik wilt maken van een van deze rechten, kunt u contact met ons opnemen via de onderstaande
                contactgegevens.</h4>

            <h5>8. Wijzigingen in de privacyverklaring</h5>
            <h4>Deze privacyverklaring kan van tijd tot tijd worden gewijzigd. Wij raden u aan deze verklaring
                regelmatig te raadplegen om op de hoogte te blijven van eventuele wijzigingen.</h4>

            <h5>9. Contactgegevens</h5>
            <h4>Als u vragen heeft over deze privacyverklaring of de verwerking van uw persoonsgegevens, kunt u contact
                met ons opnemen via:</h4>

            <h4>Sole Machina</h4>
            <h4>Pizzastraat 25</h4>
            <h4>SoleMachina@gmail.com</h4>
            <h4>0683729545</h4>
        </div>
    </section>
</body>