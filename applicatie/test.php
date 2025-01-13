<?php

require_once 'db_connectie.php';

$db = maakVerbinding();

$sql = 'select * from stuk';
$dataset = $db->query($sql);

function toonTabelInhoud($dataset): string
{
    $html = '<table>';
    foreach ($dataset as $row) {
        $html .= '<tr>';
        for ($i = 0; $i < (count(value: $row) / 2); $i++) {
            $html .= '<td>' . $row[$i] . '</td>';
        }
        $html .= '</tr>';
    }
    $html .= '</table>';
    return $html;
}

function toonTabel($db, $tabel): string
{
    $sql = "select * from {$tabel}";
    $dataset = $db->query($sql);
    $html = "<h2></h2>";
    $html .= toonTabelInhoud($dataset);
    return $html;
}

?>

<body>
    <?php
    echo toonTabelInhoud(dataset: $dataset);
    ?>
</body>