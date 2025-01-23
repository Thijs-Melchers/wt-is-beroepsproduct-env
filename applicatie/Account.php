<?php
session_start();

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
    if (isset($_POST['username'], $_POST['password'], $_POST['role'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $role = $_POST['role'];

        if (!empty($username) && !empty($password) && !empty($role)) {
            try {
                $sql = "SELECT id, username, password, role FROM users WHERE LOWER(username) = LOWER(:username) AND LOWER(role) = LOWER(:role)";
                $stmt = $verbinding->prepare($sql);

                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':role', $role);
                $stmt->execute();

                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user && $password === $user['password']) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];

                    if ($user['role'] === 'medewerker') {
                        header('Location: Index.php');
                    } else {
                        header('Location: Index.php');
                    }
                    exit;
                } else {
                    $error = "Onjuiste gebruikersnaam, wachtwoord of rol.";
                }
            } catch (PDOException $e) {
                $error = "Fout bij inloggen: " . $e->getMessage();
            }
        } else {
            $error = "Vul alle velden in!";
        }
    } else {
        $error = "Niet alle formuliergegevens zijn ingevuld!";
    }
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

            <form action="Account.php" method="post" style="display: inline;">
                <button type="submit" id="UitlogKnop">Uitloggen</button>
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
        <form action="" method="post">
            <h4>Inloggen</h4>

            <label for="username">Naam: *</label>
            <input type="text" id="username" name="username" maxlength="20" required placeholder="Vul hier uw naam in">

            <label for="password">Wachtwoord: *</label>
            <input type="password" id="password" name="password" required placeholder="Vul hier uw wachtwoord in">

            <label for="role">Rol: *</label>
            <select id="role" name="role" required>
                <option value="">Selecteer uw rol</option>
                <option value="klant">Klant</option>
                <option value="medewerker">Medewerker</option>
            </select>

            <input type="submit" value="Inloggen">

            <?php if ($error): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>

            <p class="register-link">Nog geen account? <a href="Registreren.php">Registreer hier</a></p>
        </form>
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