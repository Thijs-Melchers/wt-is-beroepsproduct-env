<?php
require_once("db_connectie.php");
maakVerbinding();
session_start();

$verbinding->exec('USE Inloggen');

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['Naam'];
    $password = $_POST['Wachtwoord'];
    $role = $_POST['Rol'];
    $adres = $_POST['Adres'];

    if (!empty($username) && !empty($password) && !empty($role) && !empty($adres)) {
        try {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (username, password, role, adres) VALUES (:username, :password, :role, :adres)";
            $stmt = $verbinding->prepare($sql);

            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->bindParam(':role', $role);
            $stmt->bindParam(':adres', $adres);

            if ($stmt->execute()) {
                $lastUserId = $verbinding->lastInsertId();

                $sql = "SELECT id, username, role, adres FROM users WHERE id = :id";
                $stmt = $verbinding->prepare($sql);
                $stmt->bindParam(':id', $lastUserId);
                $stmt->execute();
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['adres'] = $user['adres'];

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
}
?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="normalise.css">
    <link rel="stylesheet" href="Pizzaria.css">
    <title>Pizza Sole Machina</title>
</head>

<body>
    <header>
        <a id="Logo" href=""><img src="Images/Logo.png" alt="Logo"></a>
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
            <li><a id="Overzicht" href="BestellingAfgerond.php"><img src="Images/Profile.png" alt="Profile"></a></li>
            <li><a id="Menu" href="index.php"><img src="Images/Menu.png" alt="Menu"></a></li>
            <li><a id="Winkelwagen" href="Winkelwagen.php"><img src="Images/Winkelwagen.webp" alt="Winkelwagen"></a>
            </li>
        </ul>
    </nav>
    <section>
        <article>
            <h4>Registreren</h4>
            <form action="" method="post">
                <label for="Naam">Naam: *</label>
                <input type="text" id="Naam" name="Naam" maxlength="20" required placeholder="Vul hier uw naam in">

                <label for="Wachtwoord">Wachtwoord: *</label>
                <input type="text" id="Wachtwoord" name="Wachtwoord" required placeholder="Vul hier uw wachtwoord in">

                <label for="Rol">Rol: *</label>
                <select id="Rol" name="Rol" required>
                    <option value="">Kies uw rol</option>
                    <option value="klant">Klant</option>
                    <option value="medewerker">Medewerker</option>
                </select>

                <label for="Adres">Adres: *</label>
                <input type="text" id="Adres" name="Adres" required placeholder="Vul hier uw adres in">

                <input type="submit" value="Registreren"><br>

                <a href="Account.php">
                    <button type="button">Terug naar Inloggen</button>
                </a>

                <?php if ($error): ?>
                    <p class="error"><?php echo $error; ?></p>
                <?php endif; ?>
            </form>
        </article>
    </section>

    <footer>
        <div>
            <p>Telefoonnummer: 0693849241</p>
        </div>
        <div>
            <a href="Info.php">
                <p>Contact</p>
            </a>
        </div>
        <div>
            <a href="Info.php">Informatie</a>
        </div>
    </footer>
</body>

</html>