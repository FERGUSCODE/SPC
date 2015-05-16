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
        $prensa_ids = array(1, 2, 4);
        @$plantilla_id = $prensa[0]->id;

        for ($x = 0, $prensa_ids_length = sizeof($prensa_ids), $current; $x < $prensa_ids_length; ++$x) {
            $current = $prensa_ids[$x];
            $prensaes = $this->prensa->get_all($plantilla_id, $current);
            $ccontador = sizeof($prensaes);
        ?>
       
       <!--  <?php print_r($prensaes);?>-->
        <script>
        $(function () {
        $('#container<?php echo $current; ?>').highcharts({
        title: {
        text: 'Presion Hidraulica - Prensa Nº<?php echo $current; ?>',
            x: -20 //center
        },
        xAxis: {
        categories: [<?php 
        foreach($prensaes as $c){
        echo "'".$c->prensa_inicio."',";
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
            echo "140,";} ?>]
        },

        {
            name: 'Medio',
            data: [<?php for($i = 1 ; $i <= $ccontador; $i++){
            echo "130,";} ?>]
        },
         {
            name: 'Cº',
            data: [<?php 
        foreach($prensaes as $c){
        echo $c->prensa_valor.",";
        }
        ?>]
        }, {
            name: 'Minimo',
            data: [<?php for($i = 1 ; $i <= $ccontador; $i++){
            echo "120,";} ?>]
        }]
        });
        });
        </script>
        <?php } ?>
        <script src="/spc/contents/js/highcharts.js"></script>
        <script type="text/javascript" src="/spc/contents/js/gray.js"></script>
                <script>
window.onload = function () {setTimeout(function () {location.replace('/spc/graficos/secador')}, 7000)};
</script>
    </head>
        <body>
        <div class="container-fluid">
        <div class="row">
        <div class="col-md-6" id="container1" style="min-width: 300x; height: 450px; margin: 0"></div>
        <div class="col-md-6" id="container2" style="min-width: 300x; height: 440px; margin: 0"></div>
        </div> 
        </div> 
        <div class="container-fluid">
        <div class="row">
        <div class="col-md-3" id="container99" style="min-width: 300x; height: 440px; margin: 0"></div> 
        <div class="col-md-6" id="container4" style="min-width: 300x; height: 440px; margin: 0"></div>    
        <div class="col-md-3" id="container99" style="min-width: 300x; height: 440px; margin: 0"></div>          
        </div> 
        </div>      
        </body>