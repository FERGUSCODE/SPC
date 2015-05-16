<!DOCTYPE HTML>
        <html>
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">

        <title>GRAFICOS</title>
        <link rel="stylesheet" type="text/css" href="/spc/contents/css/bootstrap_celeste.css">
        <script type="text/javascript" src="/spc/contents/js/jquery.min.js"></script>
        <style type="text/css">
        ${demo.css}
        </style>
    </head>
        <?php
        $secador_ids = array(1, 2);
        @$plantilla_id = $secador[0]->id;

        for ($x = 0, $secador_ids_length = sizeof($secador_ids), $current; $x < $secador_ids_length; ++$x) {
            $current = $secador_ids[$x];
            $secadores = $this->secador->get_all($plantilla_id, $current);
            $ccontador = sizeof($secadores);
        ?>
       
       <!--  <?php print_r($secadores);?>-->
        <script>
        $(function () {
        $('#container<?php echo $current; ?>').highcharts({
        title: {
        text: '% Humedad de Salida de Harina - Secador Nº<?php echo $current; ?>',
            x: -20 //center
        },
        xAxis: {
        categories: [<?php 
        foreach($secadores as $c){
        echo "'".$c->secador_inicio."',";
        }
        ?>]
        },
        yAxis: {
            title: {
                text: ''
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            valueSuffix: '°C'
        },
        series: [{
            name: 'Maximo',
            colors: "#7798BF",
            data: [<?php for($i = 1 ; $i <= $ccontador; $i++){
            echo "9,";} ?>]
        },

        {
            name: 'Medio',
            data: [<?php for($i = 1 ; $i <= $ccontador; $i++){
            echo "8,";} ?>]
        },
         {
            name: 'Cº',
            data: [<?php 
        foreach($secadores as $c){
        echo $c->secador_valor.",";
        }
        ?>]
        }, {
            name: 'Minimo',
            data: [<?php for($i = 1 ; $i <= $ccontador; $i++){
            echo "7,";} ?>]
        }]
        });
        });
        </script>
        <?php } ?>
        <script src="/spc/contents/js/highcharts.js"></script>
        <script type="text/javascript" src="/spc/contents/js/gray.js"></script>
                <script>
window.onload = function () {setTimeout(function () {location.replace('/spc/graficos/psconcentrado')}, 7000)};
</script>
    </head>
        <body>
        <div class="container">
        <div id="container1" style="min-width: 300x; height: 400px; margin: 0 auto"></div><br>
        <div id="container2" style="min-width: 300x; height: 400px; margin: 0 auto"></div>
        </div> 
        </div>          
        </body>