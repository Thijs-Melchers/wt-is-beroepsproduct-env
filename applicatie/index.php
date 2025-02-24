<?php
require_once("db_connectie.php");
require_once("Uitloggen.php");
maakVerbinding();
session_start();
Uitloggen();

$verbinding->exec('USE Inloggen');

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['username'], $_POST['password'], $_POST['role'])) {
        $username = trim($_POST['username']);
        $password = $_POST['password'];
        $role = $_POST['role'];

        if (!empty($username) && !empty($password) && !empty($role)) {
            try {
                $sql = "SELECT id, username, password, role FROM users 
                        WHERE LOWER(username) = LOWER(:username) AND LOWER(role) = LOWER(:role)";
                $stmt = $verbinding->prepare($sql);
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':role', $role);
                $stmt->execute();

                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($user && $password === $user['password']) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['role'] = $user['role'];

                    header('Location: Index.php');
                    exit;
                } else {
                    $error = "Onjuiste gebruikersnaam, wachtwoord of rol.";
                }
            } catch (PDOException $e) {
                $error = "Fout bij inloggen: " . htmlspecialchars($e->getMessage());
            }
        } else {
            $error = "Vul alle velden in!";
        }
    } else {
        $error = "Niet alle formuliergegevens zijn ingevuld!";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['pizza'], $_GET['aantal'])) {
    $pizza = htmlspecialchars($_GET['pizza']);
    $aantal = intval($_GET['aantal']);

    if ($aantal > 0) {
        if (!isset($_SESSION['winkelwagen'])) {
            $_SESSION['winkelwagen'] = [];
        }

        if (isset($_SESSION['winkelwagen'][$pizza])) {
            $_SESSION['winkelwagen'][$pizza] += $aantal;
        } else {
            $_SESSION['winkelwagen'][$pizza] = $aantal;
        }
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

    <section class="Korting">
        <img src="Images/Pizza.Oven.jpg" alt="Pizza afbeelding">
        <img src="Images/Pizzabezorger.webp" alt="Pizza afbeelding">
    </section>

    <section>

        <div>
            <img src="Images/Pizza.Pepperoni.jpg" alt="">
            <h1> Pizza Pepperoni </h1>
            <h2>Een pizza pepperoni is een klassieke favoriet met een knapperige korst, rijk gekruide tomatensaus, en
                gesmolten mozzarella. De topping bestaat uit dunne, pittige plakjes pepperoni die tijdens het bakken
                licht krokant worden en een rokerige, hartige smaak toevoegen. Simpel, smaakvol, en altijd geliefd!</h2>
            <form action="index.php" method="GET">
                <input type="hidden" name="pizza" value="Pizza Pepperoni">
                <label for="pepperoni">Aantal:</label>
                <input type="number" id="pepperoni" name="aantal" min="1" value="1">
                <button type="submit">Toevoegen aan Winkelwagen</button>
            </form>
        </div>

        <div>
            <img src="Images/Pizza.Margherita.webp" alt="">
            <h1> Pizza Margherita </h1>
            <h2>De pizza Margherita is een eenvoudige klassieker met tomatensaus, gesmolten mozzarella en verse
                basilicum. Een Italiaanse favoriet die de pure smaken van de ingrediënten benadrukt.</h2>
            <form action="index.php" method="GET">
                <input type="hidden" name="pizza" value="Pizza Margherita">
                <label for="margherita">Aantal:</label>
                <input type="number" id="margherita" name="aantal" min="1" value="1">
                <button type="submit">Toevoegen aan Winkelwagen</button>
            </form>
        </div>

        <div>
            <img src="Images/Pizza.Carpaccio.jpg" alt="">
            <h1> Pizza Carpaccio </h1>
            <h2>Een pizza carpaccio is een luxe keuze met een dunne korst, bedekt met flinterdunne plakjes rundvlees,
                rucola, Parmezaanse kaas, pijnboompitten en een vleugje balsamicoglazuur. Een verfijnde combinatie van
                hartige en frisse smaken!</h2>
            <form action="index.php" method="GET">
                <input type="hidden" name="pizza" value="Pizza carpaccio">
                <label for="carpaccio">Aantal:</label>
                <input type="number" id="carpaccio" name="aantal" min="1" value="1">
                <button type="submit">Toevoegen aan Winkelwagen</button>
            </form>
        </div>

        <div>
            <img src="Images/Griekse.Pizza.jpg" alt="">
            <h1> Griekse Pizza </h1>
            <h2>Een Griekse pizza is een mediterrane traktatie met een knapperige korst, belegd met tomatensaus, feta,
                olijven, rode ui, tomaat en verse spinazie. Afgewerkt met oregano en een scheutje olijfolie, biedt het
                een heerlijke mix van frisse en hartige smaken!</h2>
            <form action="index.php" method="GET">
                <input type="hidden" name="pizza" value="Griekse Pizza">
                <label for="griekse">Aantal:</label>
                <input type="number" id="griekse" name="aantal" min="1" value="1">
                <button type="submit">Toevoegen aan Winkelwagen</button>
            </form>
        </div>

        <div>
            <img src="Images/Pizza.Hawaii.jpg" alt="">
            <h1> Pizza Hawaii </h1>
            <h2>De pizza Hawaii combineert tomatensaus, gesmolten kaas, ham en ananas voor een unieke mix van zoet en
                hartig. Deze Canadese creatie is geliefd en tegelijk controversieel.</h2>
            <form action="index.php" method="GET">
                <input type="hidden" name="pizza" value="Pizza Hawaii">
                <label for="hawaii">Aantal:</label>
                <input type="number" id="hawaii" name="aantal" min="1" value="1">
                <button type="submit">Toevoegen aan Winkelwagen</button>
            </form>
        </div>

        <div>
            <img src="Images/Vegetarische.Pizza.jpg" alt="">
            <h1> Veggie Pizza </h1>
            <h2>Een vegetarische pizza is rijkelijk belegd met verse groenten zoals paprika, champignons, uien, tomaten
                en olijven, vaak op een basis van tomatensaus en gesmolten kaas. Een smaakvolle keuze zonder vlees.</h2>
            <form action="index.php" method="GET">
                <input type="hidden" name="pizza" value="Pizza Veggie">
                <label for="veggie">Aantal:</label>
                <input type="number" id="veggie" name="aantal" min="1" value="1">
                <button type="submit">Toevoegen aan Winkelwagen</button>
            </form>
        </div>
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