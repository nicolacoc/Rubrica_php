<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && class_exists('DateTime')) {
    require_once('db.php');
    session_start();
    $error = false;
    $imaId = uniqid();
    $dati = new stdClass();

    $imageOriginalName = (array_key_exists('image', $_FILES)) ? explode(".", $_FILES["image"]["name"]) : null;
    $dati->imageName = (!is_null($imageOriginalName)) ? "{$imaId}.{$imageOriginalName[1]}" : null;
    $dati->Nome = (array_key_exists('Nome', $_POST)) ? $_POST["Nome"] : null;
    $dati->Cognome = (array_key_exists('Cognome', $_POST)) ? $_POST["Cognome"] : null;
    $dati->Societa = (array_key_exists('Societa', $_POST)) ? $_POST["Societa"] : null;
    $dati->Qualifica = (array_key_exists('Qualifica', $_POST)) ? $_POST["Qualifica"] : null;
    $dati->Email = (array_key_exists('Email', $_POST)) ? $_POST["Email"] : null;
    $dati->Telefono = (array_key_exists('Telefono', $_POST)) ? $_POST["Telefono"] : null;
    $db = connessione();
    $dataNascitaView = (array_key_exists('Data_di_Nascita', $_POST))?$_POST['Data_di_Nascita']:"";



    if (array_key_exists('image', $_FILES) && is_uploaded_file($_FILES['image']['tmp_name'])) {
        $notify = "Caricato con successo il file";
        $imageSRC = "img/{$dati->imageName}";
        move_uploaded_file($_FILES['image']['tmp_name'], $imageSRC);
    } else {
        $notify = "File non caricato";
        $error = true;
    }

    if (array_key_exists('Data_di_Nascita', $_POST) && DateTime::createFromFormat('d/m/Y', $_POST['Data_di_Nascita']) !== false) {
        $dataNascita = DateTime::createFromFormat('d/m/Y', $_POST["Data_di_Nascita"]);
        $dati->Data_di_Nascita = $dataNascita->format('Y-m-d');
    }elseif (strlen($_POST['Data_di_Nascita']) > 1) {
        $error = true;
        $notify = "Data di nascita non valida";
    }else{
        $dati->Data_di_Nascita = null;
    }

    if (!empty($dati->Email) && !filter_var($dati->Email, FILTER_VALIDATE_EMAIL)) {
        $notify = "Email non valida";
        $error = true;
    }



    if (is_string($db)) {
        $notify = $db;
    } elseif (!$error) {
        addToForm($db, $dati);
    }
    $db = null;

    if (!$error){
        echo "<script>setTimeout(()=>{window.location.href='index.php'},1500)</script>";
    }
} else {
    $dati = new stdClass();
    $dati->imageName = null;
    $dati->Nome = null;
    $dati->Cognome = null;
    $dati->Societa = null;
    $dati->Qualifica = null;
    $dati->Email = null;
    $dati->Telefono = null;
    $dati->Data_di_Nascita = null;
    $notify = null;
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Moduli</title>
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="Style/insert.css">
</head>
<body>
<div class="container">
    <?php if (!empty($notify)): ?>
    <div class="row">
        <div class="col bg-danger my-3 fs-1">
            <div class="text-center">
            <span class="text-dark font-weight-bold"><?php echo $notify?></span>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <?php if (!empty($imageSRC)): ?>
        <div class="card" style="width: 18rem;">
            <img src="<?php echo $imageSRC ?>" class="card-img-top" alt="your image">
        </div>
    <?php endif ?>
    <form method="post" enctype="multipart/form-data">
        <div class="input-group mb-3 mt-5">
            <input type="hidden" name="MAX_FILE_SIZE" value="2097152">
            <input type="file" accept="image/png, image/jpeg" class="form-control" id="image" name="image"
                   value="<?php echo $dati->imageName ?>" required>
            <label class="input-group-text" for="image">Upload</label>
        </div>
        <div class="input-group mb-3">
            <span class="input-group-text" id="Nome">Nome</span>
            <input type="text" class="form-control" placeholder="Nome" aria-label="Nome" aria-describedby="basic-addon1"
                   name="Nome" value="<?php echo $dati->Nome ?>" required>
        </div>
        <div class="input-group mb-3">
            <span class="input-group-text" id="Cognome">Cognome</span>
            <input type="text" class="form-control" placeholder="Cognome" aria-label="Cognome"
                   aria-describedby="basic-addon1" name="Cognome" value="<?php echo $dati->Cognome ?>">
        </div>
        <div class="input-group mb-3">
            <span class="input-group-text" id="Societa">Società</span>
            <input type="text" class="form-control" placeholder="Societa" aria-label="Societa"
                   aria-describedby="basic-addon1" name="Societa" value="<?php echo $dati->Societa ?>">
        </div>
        <div class="input-group mb-3">
            <span class="input-group-text" id="Qualifica">Qualifica</span>
            <input type="text" class="form-control" placeholder="Qualifica" aria-label="Qualifica"
                   aria-describedby="basic-addon1" name="Qualifica" value="<?php echo $dati->Qualifica ?>">
        </div>
        <div class="input-group mb-3">
            <span class="input-group-text" id="Email">Email</span>
            <input type="text" class="form-control" placeholder="Email" aria-label="Email"
                   aria-describedby="basic-addon1" name="Email" value="<?php echo $dati->Email ?>">
        </div>
        <div class="input-group mb-3">
            <span class="input-group-text" id="Telefono">Telefono</span>
            <input type="number" class="form-control" placeholder="Telefono" aria-label="Telefono"
                   aria-describedby="basic-addon1" name="Telefono" value="<?php echo $dati->Telefono ?>" required>
        </div>
        <div class="input-group mb-3">
            <span class="input-group-text" id="Data_di_Nascita">Data di Nascita</span>
            <input type="text" class="form-control" placeholder="Data_di_Nascita" aria-label="Data_di_Nascita"
                   aria-describedby="basic-addon1" name="Data_di_Nascita" value="<?php echo (!empty($dataNascitaView))?$dataNascitaView:null ?>">
        </div>
        <div class="input-group my-3">
            <button type="submit" class="btn btn-secondary m-auto my-2">Salva</button>
        </div>
    </form>
</div>
</body>
</html>