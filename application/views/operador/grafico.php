<h1><?php echo $titulo; ?></h1>
<script>
$(function () {
<?php
foreach ($datos as $maquina_id => $items) {
  $maquinaLength = sizeof($items['valor']);
?>
  var container = document.createElement('DIV');
  document.body.appendChild(container);
  container.highcharts({
    title: {
      text: 'Temperatura de Licor - Cocedor Nº<?php echo $current; ?>',
      x: -20 //center
    },
    xAxis: {
      categories: [<?php echo implode(',', $items['tiempo']); ?>]
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
      name: 'Máximo',
      colors: "#7798BF",
      data: [<?php echo implode(',', array_fill(0, $maquinaLength, $items['max'])); ?>]
    }, {
      name: 'Medio',
      data: [<?php echo implode(',', array_fill(0, $maquinaLength, ($items['min'] + $items['max']) / 2)); ?>]
    }, {
      name: <?php echo $items['unidad']; ?>,
      data: [<?php echo implode(',', $items['valor']); ?>]
    }, {
      name: 'Minimo',
      data: [<?php echo implode(',', array_fill(0, $maquinaLength, $items['min'])); ?>]
    }]
  });
<?php } ?>
});
</script>
<script src="/spc/contents/js/highcharts.js"></script>
<script src="/spc/contents/js/gray.js"></script>
<div class="container">
<div class="row">
<div id="container1" style="min-width: 300x; height: 450px; margin: 0"></div>
<div id="container3" style="min-width: 300x; height: 440px; margin: 0"></div>
<div id="container4" style="min-width: 300x; height: 440px; margin: 0"></div>            
</div> 
</div>