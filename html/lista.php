<?php
/**
 * Created by PhpStorm.
 * User: agustin
 * Date: 01/04/15
 * Time: 00:45
 */?>
<!DOCTYPE html>
<html>
<head lang="es">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <title>Circuito Rally Branca 2015</title>
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/brancaRally2015.css"/>
</head>
<body>
<section>
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                <h1 class="color-black">Lista de archivos</h1>
                <ol class="color-black">
                <?php
                $i = 0;
                $dir = $_SERVER['DOCUMENT_ROOT'] . '/html/';
                if ($handle = opendir($dir)) {
                    while (($file = readdir($handle)) !== false){
                        if (!in_array($file, array('.', '..')) && !is_dir($dir.$file) && pathinfo($file, PATHINFO_EXTENSION) == 'html') {
                            ?>

                                <li>
                                <a href="<?php echo $file;?>" target="_blank" class="alert-link"><?php echo pathinfo($file,PATHINFO_FILENAME);?></a>
                                </li>

                            <?php
                        }
                    }
                }
                ?>
                </ol>
            </div>
        </div>
    </div>
</section>

</body>