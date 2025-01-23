<?php
$db_host = 'database_server';
$db_name = 'Inloggen';
$db_user = 'sa';
$db_password = 'abc123!@#';

try {
    $verbinding = new PDO('sqlsrv:Server=' . $db_host . ';Database=' . $db_name . ';ConnectionPooling=0;TrustServerCertificate=1', $db_user, $db_password);
    $verbinding->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Verbindingsfout: " . $e->getMessage());
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['Naam'];
    $password = $_POST['Wachtwoord'];
    $role = $_POST['Rol'];

    if (!empty($username) && !empty($password) && !empty($role)) {
        try {
            $sql = "INSERT INTO users (username, password, role) VALUES (:username, :password, :role)";
            $stmt = $verbinding->prepare($sql);

            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':role', $role);

            if ($stmt->execute()) {
                header("Location: Index.php");
                exit;
            } else {
                $error = "Er is een fout opgetreden bij de registratie.";
            }
        } catch (PDOException $e) {
            $error = "Fout bij invoegen: " . $e->getMessage();
        }
    } else {
        $error = "Vul alle velden in!";
    }
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['logout'])) {
        session_unset();

        session_destroy();

        header("Location: Inloggen.php");
        exit();
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
        <a id="Logo" href="index.php"><img src="Images/Logo.png" alt="Logo"></a>
        <p>Pizza Sole Machina</p>
        <?php if (isset($_SESSION['username'])): ?>
            <form action="" method="post" style="display: inline;">
                <button type="submit" name="logout" id="UitlogKnop">Uitloggen</button>
            </form>
            <span id="WelkomTekst">Welkom, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
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

                <input type="submit" value="Registreren">

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