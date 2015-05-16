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
        $psconcentrado_ids = array(1,2);
        @$plantilla_id = $psconcentrado[0]->id;

        for ($x = 0, $psconcentrado_ids_length = sizeof($psconcentrado_ids), $current; $x < $psconcentrado_ids_length; ++$x) {
            $current = $psconcentrado_ids[$x];
            $psconcentradoes = $this->psconcentrado->get_all($plantilla_id, $current);
            $ccontador = sizeof($psconcentradoes);
        ?>
       
       <!--  <?php print_r($psconcentradoes);?>-->
        <script>
        $(function () {
        $('#container<?php echo $current; ?>').highcharts({
        title: {
        text: '% SOLIDO CONCENTRADO - EVAPORADOR Nº<?php echo $current; ?>',
            x: -20 //center
        },
        xAxis: {
        categories: [<?php 
        foreach($psconcentradoes as $c){
        echo "'".$c->psconcentrado_inicio."',";
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
            echo "42,";} ?>]
        },

        {
            name: 'Medio',
            data: [<?php for($i = 1 ; $i <= $ccontador; $i++){
            echo "40,";} ?>]
        },
         {
            name: '%',
            data: [<?php 
        foreach($psconcentradoes as $c){
        echo $c->psconcentrado_valor.",";
        }
        ?>]
        }, {
            name: 'Minimo',
            data: [<?php for($i = 1 ; $i <= $ccontador; $i++){
            echo "37,";} ?>]
        }]
        });
        });
        </script>
        <?php } ?>
        <script src="/spc/contents/js/highcharts.js"></script>
        <script type="text/javascript" src="/spc/contents/js/gray.js"></script>
                <script>
window.onload = function () {setTimeout(function () {location.replace('/spc/graficos/')}, 7000)};
</script>
    </head>
        <body>
        <div class="container">
        <div class="row">
        <div id="container1" style="min-width: 300x; height: 450px; margin: 0"></div>
        <div id="container2" style="min-width: 300x; height: 440px; margin: 0"></div>      
        </div> 
        </div>      
        </body>