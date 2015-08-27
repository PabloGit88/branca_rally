<?php
//header('Content-Type: text/html;charset=UTF-8');
/**
 * Created by PhpStorm.
 * User: Agustin
 * Date: 09/03/2015
 * Time: 04:55 PM
 */
if(!isset($_GET['equipo']) || !isset($_GET['size'])){
    header('Location: ../');
    die();
}

$equipo_sel = $_GET['equipo'];
$size = $_GET['size'];

if($equipo_sel == 'branca'){
    $equipo_otro = 'unico';
}else{
    $equipo_otro = 'branca';
}

$screen = array(
    'md' => array(
        'width' => '1024',
        'height' => '462'
    ),
    'sm' => array(
        'width' => '768',
        'height' => '319'
    )

);

$pistas = array(
    'md'=>array(
        'perdedor' => array(
            'bridge' => array('max'=> '2430' , 'min' => '2200'),
            'svg'=> 'M422.167,163.491c20.482-28.518,28.295-72.23,30.777-79.681 c3.375-10.123,25.644-41.84,49.263-26.994c23.619,14.846,76.932,66.807,126.869,64.108c49.936-2.699,145.085,3.374,161.283-4.049 c16.194-7.424,29.855,13.126,35.931,23.249c1.398,2.333,12.677,16.479,21.268,22.619c9.92,7.09,30.596,1.463,44.225,9.79 c25.774,15.749,39.664,25.29,48.95,28.357c12.347,4.078,58.065,18.23,64.22,59.723c5.845,39.405-17.039,78.957-47.99,68.09 c-10.896-3.825-33.035-27.657-45.6-31.938c-11.585-3.948-27.702,10.337-42.198,13.166c-13.842,2.7-33.106,0.912-48.513,5.003 c-28.07,7.452-34.341,27.332-61.333,25.309c-26.994-2.025-43.19-20.92-72.208-39.815s-74.853-47.887-115.362-47.549 c-36.839,0.308-61.104,8.439-87.773,55.027c-13.264,23.168-19.243,83.047-77.591,86.997c-42.849,2.901-85.704-31.045-97.176-53.987 c-11.471-22.943-57.495-77.234-118.261-52.928c-60.703,24.281-74.738,25.934-103.756,0.291 c-29.017-25.643-9.447-67.483,13.497-94.475c22.943-26.993,80.131-80.35,117.42-80.98c40.69-0.688,53.068,21.002,86.606,31.013 c22.619,6.752,38.181-7.257,44.983-14.138c0,0,19.57-24.293,43.189-19.57c23.619,4.724,68.832,73.556,93.8,60.06 c24.969-13.496,59.385-30.368,107.973-8.772c48.588,21.594,164.658,48.588,186.926,50.613c22.27,2.024,92.453-0.675,103.924,14.17 c11.472,14.846,7.425,42.515-43.862,45.889c-51.287,3.373-70.183,6.073-114.047-14.172 c-43.863-20.244-196.374-73.557-238.889-38.466c-42.514,35.092-31.717,56.013-50.611,77.606 c-18.896,21.594-54.662,41.164-78.956,13.496c-24.294-27.668-76.931-82.33-136.316-80.307 c-59.384,2.024-69.507-41.839-29.018-47.238c40.49-5.398,84.353-3.375,107.298,8.098c22.943,11.472,62.912,22.456,97.724-0.449 L422.167,163.491z'
        ),
        'ganador' => array(
            'bridge' => array('max'=> '200' , 'min' => '34'),
            'svg'=>'M387,109.5c7.333-8.444,11.168-9.884,26.835-6.384 c23.414,5.231,48.695,80.215,73.565,66.772c24.871-13.443,59.151-30.248,107.547-8.738 c48.396,21.509,164.008,48.396,186.188,50.413c22.183,2.016,92.088-0.672,103.516,14.114c11.426,14.787,7.394,42.347-43.691,45.708 c-51.084,3.359-69.905,6.05-113.596-14.117c-43.69-20.164-195.6-73.266-237.947-38.313c-42.346,34.952-31.591,55.791-50.411,77.3 c-18.821,21.509-54.446,41.001-78.644,13.442c-24.198-27.559-76.628-82.005-135.779-79.989 c-59.149,2.017-69.232-41.674-28.902-47.051c40.33-5.376,79.64,0.484,102.731,11.422c31.944,15.131,70.836,8.091,92.134-6.725 c23.202-16.141,60.53-96.175,63.889-106.258c3.362-10.083,23.55-39.335,47.076-24.546c23.526,14.787,50.438,53.801,127.716,64.268 c54.201,7.341,130.655,3.019,147.617-2.2c30.745-9.46,53.188,24.458,59.916,34.102c10.087,14.459,28.777,9.164,52.455,21.184 c20.133,10.221,37.886,23.156,50.775,24.883c32.616,4.371,59.518,13.47,58.173,53.128c-1.344,39.657-4.143,81.438-40.015,77.34 c-16.046-1.834-34.257-27.983-48.422-30.601c-13.01-2.402-26.932,10.823-54.137,12.105c-55.365,2.612-69.749,30.526-96.635,28.511 c-26.887-2.018-38.894-24.82-67.796-43.642c-28.903-18.821-77.003-42.369-126.769-42.705c-36.692-0.248-54.81,12.778-71.613,38.622 c-14.495,22.293-31.617,101.262-85.364,102.17c-42.319,0.716-79.747-19.739-110.683-65.471 c-14.316-21.161-43.54-59.472-98.187-41.023c-61.7,20.829-80.16,25.832-109.063,0.291c-28.902-25.542,0.825-80.316,23.679-107.202 c22.853-26.887,34.609-61.89,106.721-67.562c15.471-1.217,40.329,18.821,73.266,30.248c32.936,11.427,79.092-19.346,85.868-26.2 L387,109.5z'
        )
    ),
    'sm' => array(
        'perdedor' => array(
            'bridge' => array('max'=> '1900' , 'min' => '1700'),
            'svg'=>'M317.206,121.718c15.152-21.096,20.932-53.434,22.768-58.945 c2.497-7.489,18.971-30.952,36.444-19.969c17.473,10.983,56.911,49.422,93.854,47.425c36.94-1.997,107.328,2.496,119.312-2.995 c11.979-5.492,22.086,9.71,26.58,17.199c1.034,1.726,9.378,12.19,15.733,16.732c7.338,5.245,22.634,1.082,32.716,7.242 c19.066,11.65,29.342,18.709,36.212,20.978c9.133,3.017,42.954,13.486,47.507,44.181c4.324,29.151-12.604,58.41-35.501,50.371 c-8.062-2.83-24.438-20.46-33.733-23.626c-8.57-2.921-20.493,7.646-31.217,9.739c-10.239,1.998-24.491,0.675-35.888,3.701 c-20.766,5.513-25.404,20.22-45.372,18.723c-19.97-1.499-31.951-15.477-53.417-29.454c-21.467-13.979-55.374-35.425-85.342-35.175 c-27.252,0.228-45.202,6.243-64.932,40.708c-9.812,17.139-14.234,61.436-57.399,64.357c-31.698,2.146-63.401-22.966-71.887-39.938 c-8.486-16.973-42.533-57.135-87.486-39.153c-44.906,17.962-55.289,19.185-76.755,0.215s-6.989-49.922,9.984-69.89 c16.973-19.968,59.278-59.44,86.863-59.906c30.101-0.508,39.258,15.537,64.069,22.943c16.732,4.995,28.245-5.369,33.277-10.459 c0,0,14.478-17.972,31.95-14.478c17.473,3.494,50.92,54.414,69.391,44.43c18.472-9.984,43.931-22.465,79.875-6.49 c35.943,15.975,121.809,35.944,138.281,37.441c16.474,1.498,68.394-0.5,76.879,10.483c8.486,10.982,5.493,31.451-32.447,33.947 c-37.94,2.495-51.919,4.492-84.368-10.484c-32.449-14.976-145.271-54.415-176.722-28.456c-31.45,25.96-23.463,41.436-37.44,57.411 c-13.978,15.975-40.437,30.452-58.409,9.984s-56.911-60.905-100.842-59.409c-43.93,1.498-51.418-30.951-21.466-34.945 c29.953-3.993,62.402-2.496,79.376,5.99c16.973,8.487,46.54,16.612,72.293-0.332L317.206,121.718z',
        ),
        'ganador' => array(
            'bridge' => array('max'=> '180' , 'min' => '40'),
            'svg'=>'M291.968,84.554c5.391-6.207,8.209-7.266,19.725-4.693 c17.21,3.845,35.792,58.962,54.074,49.081c18.28-9.881,43.478-22.233,79.051-6.422c35.572,15.81,120.552,35.573,136.854,37.055 c16.306,1.482,67.688-0.494,76.089,10.375c8.397,10.869,5.434,31.126-32.116,33.597c-37.548,2.469-51.383,4.447-83.497-10.376 c-32.114-14.821-143.773-53.853-174.9-28.161c-31.126,25.691-23.22,41.008-37.053,56.819c-13.835,15.81-40.021,30.136-57.807,9.881 c-17.787-20.257-56.325-60.277-99.803-58.796c-43.477,1.482-50.888-30.633-21.245-34.584c29.644-3.952,58.539,0.356,75.512,8.396 c23.48,11.122,52.066,5.947,67.722-4.943c17.054-11.864,44.493-70.693,46.961-78.104c2.471-7.411,17.31-28.913,34.602-18.042 c17.293,10.869,37.074,39.545,93.875,47.239c39.841,5.396,96.037,2.219,108.505-1.617c22.6-6.954,39.095,17.977,44.041,25.066 c7.414,10.627,21.153,6.735,38.557,15.571c14.799,7.513,27.847,17.02,37.322,18.29c23.974,3.213,43.747,9.901,42.758,39.051 c-0.987,29.15-3.044,59.861-29.412,56.847c-11.793-1.347-25.179-20.568-35.592-22.491c-9.563-1.766-19.796,7.955-39.791,8.897 c-40.696,1.92-51.271,22.438-71.031,20.956c-19.763-1.483-28.588-18.244-49.833-32.078s-56.6-31.143-93.18-31.39 c-26.97-0.182-40.287,9.393-52.638,28.39c-10.654,16.386-23.24,74.431-62.746,75.098c-31.106,0.526-58.617-14.508-81.356-48.123 c-10.523-15.555-32.003-43.714-72.171-30.153c-45.352,15.31-58.921,18.986-80.166,0.213c-21.244-18.774,0.606-59.036,17.405-78.797 c16.797-19.762,25.438-45.491,78.444-49.66c11.372-0.895,29.644,13.834,53.853,22.233c24.209,8.399,58.136-14.22,63.116-19.258 L291.968,84.554z'
        )
    )
);

$limitPerdedor = '{max :'.$pistas[$size]['perdedor']['bridge']['max'] .', min: '. $pistas[$size]['perdedor']['bridge']['min'] .'}';
$limitGanador =  '{max :'.$pistas[$size]['ganador']['bridge']['max'] .', min: '. $pistas[$size]['ganador']['bridge']['min'] .'}';

?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Branca Rally 2015 - Race</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <link rel="stylesheet" href="../css/carrera.css"/>
</head>
<body data-seccion="carrera" class="pista-bg-pasto">

<div class="sr-only">
    <svg class="absolute" width="<?php echo $screen[$size]['width'];?>" height="<?php echo $screen[$size]['height'];?>" version="1.1" id="ganador_recorrido" xmlns="http://www.w3.org/2000/svg" >
        <g>
            <path fill="none" stroke="none" stroke-miterlimit="10" d="<?php echo $pistas[$size]['ganador']['svg']?>"></path>
        </g>
    </svg>

    <svg class="absolute" width="<?php echo $screen[$size]['width'];?>" height="<?php echo $screen[$size]['height'];?>" version="1.1" id="perdedor_recorrido" xmlns="http://www.w3.org/2000/svg">
        <g>
            <path fill="none" stroke="none" stroke-miterlimit="10" d="<?php echo $pistas[$size]['perdedor']['svg']?>"></path>
        </g>
    </svg>
</div>

<div class="container">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <!-- Trackers -->
            <canvas class="absolute" id="ganador" width="<?php echo $screen[$size]['width'];?>" height="<?php echo $screen[$size]['height'];?>"></canvas>
            <canvas class="absolute" id="perdedor" width="<?php echo $screen[$size]['width'];?>" height="<?php echo $screen[$size]['height'];?>"></canvas>
            <!-- Trackers -->
            <!-- AUTOS -->
            <div data-limit-ganador='<?php echo $limitGanador;?>' data-limit-perdedor='<?php echo $limitPerdedor;?>' class="absolute pista-puente-container <?php echo $size;?>" id="bridge"><img src="../img/carrera/puente-<?php echo $screen[$size]['width'];?>.png" alt="puente"/></div>
            <div class="absolute auto-tracker-<?php echo $size;?>" id="contenedor_ganador" data-name="<?php echo $equipo_sel;?>">
                <div id="auto_ganador" class="auto-container">
                    <div class="auto auto-<?php echo $equipo_sel;?>"></div>
                </div>
            </div>

            <div class="absolute auto-tracker-<?php echo $size;?>" id="contenedor_perdedor" data-name="<?php echo $equipo_otro;?>">
                <div id="auto_perdedor" class="auto-container">
                    <div class="auto auto-<?php echo $equipo_otro;?>"></div>
                </div>
            </div>
            <!-- AUTOS -->

            <div class="pista <?php echo $size;?> pista-<?php echo $screen[$size]['width'];?>"></div>
        </div>
    </div>
</div>

<div id="timerContent" data-counter="3" class="timer-content">
    <span id="timer" class="timer-count"><span class="sr-only">TIMER</span></span>
</div>

<script type="text/javascript" src="../js/vendor/paper.js"></script>
<script type="text/javascript" src="../js/vendor/animframe.js"></script>
<script type="text/javascript" src="../js/app/classes/SoundControl.js"></script>
<script type="text/javascript" src="../js/app/classes/Timer.js"></script>
<script type="text/javascript" src="../js/app/classes/Track.js"></script>
<script type="text/javascript" src="../js/app/classes/Car.js"></script>
<script type="text/javascript" src="../js/app/classes/AnimatePng.js"></script>
<script type="text/javascript" src="../js/app/controller/carrera.js"></script>

</body>
</html>
