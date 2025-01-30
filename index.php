<?php
require_once('db.php');
$db = connessione();
if (is_string($db)) {
    $notify = $db;
}

if (array_key_exists('Search', $_POST)) {
    $search = $_POST['Search'];
}else{
    $search = null;
}
if (empty($notify)) {
    $toForm = getToForm($db, $search);
}else{
    $toForm=[];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rubrica</title>
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <?php if (!empty($notify)): ?>
        <div class="row">
            <div class="col bg-danger my-3 fs-1">
                <div class="text-center">
                    <span class="text-dark font-weight-bold"><?php echo $notify ?></span>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="d-flex justify-content-center">
        <div class="card" style="width: 40rem;">
            <div class="card-body text-end"><a href="Insert.php">Inserisci</a></div>
            <div class="card-body">
                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
                <div class="input-group mb-3">

                    <input name="Search" type="text" class="form-control" placeholder="Search" aria-label="Search"
                           aria-describedby="Search_button">
                    <button class="btn btn-outline-secondary" type="submit" id="Search_button">Search</button>

                </div>
                    </form>
            </div>
            <div class="card-body">
                <ul class="list-group">
                <!--                -->
                    <?php foreach ($toForm as $form): ?>
                    <?php

                    if(!empty($form['Data_di_Nascita'])){
                        $Data_di_Nascita = DateTime::createFromFormat('Y-m-d' , $form['Data_di_Nascita']);
                        $Nascita_view = $Data_di_Nascita->format('d/m/Y');
                    }else{
                        $Nascita_view = "&nbsp;";
                    }
                        if (!empty($form['Nome'])&&!empty($form['Cognome'])){
                   $iniziali= strtoupper($form['Nome'][0]. $form['Cognome'][0]);

                        }else{
                            $iniziali = "";
                        } ?>


                            <li class="list-group-item lista">
                                <div class="ima">
                                <?php if (!empty($form['Ima']) && file_exists('img/'.$form['Ima'])): ?>
                                <img src="img/<?php echo $form['Ima']?>" alt="image">
                                <?php else:?>
                                <img src="https://placehold.co/150x150?text=<?php echo $iniziali?>" alt="image">
                                <?php endif; ?>
                                </div>
                                <div class="card-text">
                                    <ul>
                                        <li><?php echo $form['Nome'].'&nbsp;'.$form['Cognome']?></li>
                                        <li><?php echo $form['Societa']?></li>
                                        <li><?php echo $form['Qualifica']?></li>
                                        <li><?php echo $form['Telefono']?></li>
                                        <li><?php echo $form['Email']?></li>
                                        <li><?php echo $Nascita_view?></li>
                                    </ul>
                                </div>
                            </li>

                    <?php endforeach; ?>
                        </ul>
                <!--                -->
            </div>
        </div>
    </div>


</div>
<script src="node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>