<?php
// Estrazione e pulizia dei dati inviati tramite POST
$nome = isset($_POST['nome']) ? htmlspecialchars(trim($_POST['nome'])) : '';
$peso = isset($_POST['peso']) ? floatval($_POST['peso']) : 0;
$altezza_cm = isset($_POST['altezza']) ? floatval($_POST['altezza']) : 0;

// Validazione ed elaborazione del BMI
if ($peso > 0 && $altezza_cm > 0) {
    $altezza_m = $altezza_cm / 100;
    $bmi = $peso / ($altezza_m * $altezza_m);
    $bmi_formatted = number_format($bmi, 2, '.', '');

    // Determinazione della fascia di peso
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
} else {
    // In caso di dati non validi
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
            <div class="user-name"><?php echo $nome; ?></div>
            <div class="user-params">Peso <?php echo $peso; ?> kg - Altezza <?php echo $altezza_cm; ?> cm</div>
            <div class="bmi-value"><?php echo $bmi_formatted; ?></div>
            <div class="bmi-category"><?php echo $categoria; ?></div>
        </div>
        <a href="form.html" class="reset-link">Calcola di nuovo</a>
    </div>
</body>
</html>