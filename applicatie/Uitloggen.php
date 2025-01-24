<?php
function Uitloggen()
{
    if (isset($_POST['logout'])) {
        session_unset();
        session_destroy();
        header("Location: account.php");
        exit();
    }
}
?>