<?php

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: form.html");
    exit();
}


$ip_address = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'UNKNOWN_IP';
$data_ora = date('d/m/Y,H:i:s');

$raw_nome = isset($_POST['nome']) ? $_POST['nome'] : '';
$raw_peso = isset($_POST['peso']) ? $_POST['peso'] : '';
$raw_altezza = isset($_POST['altezza']) ? $_POST['altezza'] : '';


$nome_valido = !empty($raw_nome) && trim($raw_nome) !== '';
$peso_valido = is_numeric($raw_peso) && (float)$raw_peso > 0;
$altezza_valida = is_numeric($raw_altezza) && (float)$raw_altezza > 0;


$dati_validi = $nome_valido && $peso_valido && $altezza_valida;


$log_nome = $nome_valido ? htmlspecialchars(trim($raw_nome)) : 'NOT_VALID';
$log_altezza = $altezza_valida ? (float)$raw_altezza : 'NOT_VALID';
$log_peso = $peso_valido ? (float)$raw_peso : 'NOT_VALID';

if ($dati_validi) {
    
    $altezza_m = $log_altezza / 100;
    $bmi = $log_peso / ($altezza_m * $altezza_m);
    $bmi_formatted = number_format($bmi, 2, '.', '');

    
    if ($bmi < 18.5) {
        $classe_bmi = "sottopeso";
        $categoria = "SOTTOPESO";
    } elseif ($bmi <= 24.9) {
        $classe_bmi = "normopeso";
        $categoria = "NORMOPESO";
    } elseif ($bmi <= 29.9) {
        $classe_bmi = "sovrappeso";
        $categoria = "SOVRAPPESO";
    } else {
        $classe_bmi = "obesita";
        $categoria = "OBESITÀ";
    }

    $log_bmi = $bmi_formatted;
    $log_class = $categoria;
} else {
    $log_bmi = 'NOT_VALID';
    $log_class = 'NOT_VALID';
}

$log_line = sprintf(
    "%s,%s,%s,%s,%s,%s,%s\n",
    $data_ora,
    $ip_address,
    $log_nome,
    $log_altezza,
    $log_peso,
    $log_bmi,
    $log_class
);

file_put_contents('log.csv', $log_line, FILE_APPEND | LOCK_EX);

if (!$dati_validi) {
    header("Location: form.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Risultato BMI</title>
    <link rel="stylesheet" href="stile.css">
</head>
<body>
    <div class="result-wrapper" style="text-align: center;">
        <div class="card <?php echo $classe_bmi; ?>">
            <div class="user-name"><?php echo $log_nome; ?></div>
            <div class="user-params">Peso <?php echo $log_peso; ?> kg - Altezza <?php echo $log_altezza; ?> cm</div>
            <div class="bmi-value"><?php echo $log_bmi; ?></div>
            <div class="bmi-category"><?php echo $log_class; ?></div>
        </div>
        <a href="form.html" class="reset-link">Calcola di nuovo</a>
    </div>
</body>
</html>