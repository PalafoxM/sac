<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
       
        /* @font-face {
            font-family: 'ProximaNova'; 
            src: url(<?php echo FCPATH?>'assets/fonts/custom/proxima-nova.otf') format('opentype');
        } */
       
        .container{
            /* border:3px solid red; */
            margin: 0;
            padding: 0;
            left:0%;
            top:0;
            position: absolute;
            width:100%;
            height: 100%;
            background-image: url('<?= $dataImagen ?>');
            background-size:100% 100%;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        /* .proxima{
            font-family: 'BalooRegular', sans-serif;
        } */
        /* .proxima{
            font-family: 'ProximaNova', sans-serif;
        } */
        .folio{
            width:13%;
            color:#ecf7ff;
            font-weight: bold;
            /* border:1px solid red; */
        }
        .textResumen{
            font-size: 12px;
            text-align: justify;
        }
        .textList{
            font-size: 13px;
            text-align: justify;
            list-style: none;
        }
        .textTurnado{
            font-size: 12px;
            text-align: justify;
        }
        .resumen{
            
            width:83%;
        }
        .fecha{
            font-size: 12px;
           
        }
        .bordeRojo{
            /* border: 1px solid red; */
        }
    </style>
    
<body>
   

    <?php date_default_timezone_set('America/Mexico_City');
        $fechaActual = date('Y-m-d');
        setlocale(LC_TIME, 'es_MX'); 
        $fechaFormateada = strftime('%d de %B del %y', strtotime($fechaActual));
    ?>
    <div class='container'>

        <div class="bordeRojo" style="position:absolute; margin-left: 65.5%; margin-top:5.3%;width:30%; height:18px">
             <small class="proxima fecha">Guanajuato, Gto, <?= $fechaFormateada; ?></small>
        </div>
        <div class="folio bordeRojo" style="position:absolute; margin-left: 74%; margin-top:2.1%;width:15%;height:18px">
            <span class="proxima "><?= $dataPage['id_turno']."/".$dataPage['anio']; ?></span>
        </div>

        <div class="bordeRojo" style="position:absolute; margin-left: 75.7%; margin-top:2.6%;width:17%; height:18px;">
            <span class="proxima "><?= strtoupper($dataPage['usuario_registro']); ?></span>
        </div>
        <div class="textTurnado bordeRojo" style="position:absolute; margin-left: 4%; margin-top:3%;width:80%; height:70px">
            <ul style=" list-style:none;">
                <?php 
                foreach ( $dataPage['turnado'] as $turnado) { 
                    echo '<li>'. $turnado->nombre_destinatario .' '. $turnado->cargo.'</li>';
                }
                ?>
            </ul> 
        </div>
        <div class="textTurnado bordeRojo" style="position:absolute; margin-left: 18%; margin-top:14%;">
            <span class="proxima "><strong><?= $dataPage['asunto']; ?></strong></span>
        </div>
        <div class="textTurnado bordeRojo" style="margin-left: 9%; margin-top:0.5%;">
            <span class="proxima "><?= strtoupper($dataPage['nombre_completo']); ?></span>
        </div>
        <div class="textTurnado bordeRojo" style="margin-left: 9%; margin-top:0.5%;">
            <span class="proxima "><?= strtoupper($dataPage['cargo']); ?></span>
        </div>
        <div class="textTurnado bordeRojo" style="margin-left: 9%; margin-top:0.5%;">
            <span class="proxima "><?= strtoupper($dataPage['razon_social']); ?></span>
        </div>
        <div class="bordeRojo" style="margin-left: 9%; margin-top:0.5%;">
            <span class="proxima "><strong><?= strtoupper($dataPage['fecha_recepcion']); ?></strong></span>
        </div>
        <div class="resumen bordeRojo" style="margin-left: 9%; margin-top:5%;">
            <span class="proxima textResumen"><?= $dataPage['resumen']; ?></span>
        </div>
        <div class="textList bordeRojo" style="margin-left: 9%; margin-top:14%;">
            <ul>
                <?php 
                foreach ( $dataPage['indicaciones'] as $indicacion) { 
                    echo '<li>' . $indicacion->dsc_indicacion . '</li>';
                }
                ?>
            </ul> 
        </div>
        <div class="textList bordeRojo" style="margin-left: 9%; margin-top:3%;">
           <ul>
               <?php 
                foreach ( $dataPage['destinatarioCopia'] as $destinatario) { 
                    echo '<li>' . $destinatario->nombre_destinatario . '</li>';
                }
                ?>
            </ul>
        </div>
        
    </div>
    
</body>
</html>