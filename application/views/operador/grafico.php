<div class="container">
<h1><?php echo $titulo . ' - ' . $fecha; ?></h1>
<script>
$(function () {
<?php
$maquinaLength = sizeof($datos);

foreach ($datos as $maquina_id => $items) {
  $valorLength = sizeof($items['valor']);
?>
  var container = document.createElement('DIV');
  container.className = 'col-md-<?php echo (int) (12 / $maquinaLength); ?>';
  $('.row')[0].appendChild(container);
  $(container).highcharts({
    title: {
      text: '<?php echo $items['nombre']; ?>',
      x: -20
    },
    xAxis: {
      categories: ['<?php echo implode("','", $items['tiempo']); ?>']
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
      valueSuffix: '<?php echo $items['unidad']; ?>'
    },
    series: [{
      name: 'Máximo',
      colors: '#7798BF',
      data: [<?php echo implode(',', array_fill(0, $valorLength, $items['max'])); ?>]
    }, {
      name: 'Medio',
      data: [<?php echo implode(',', array_fill(0, $valorLength, ($items['min'] + $items['max']) / 2)); ?>]
    }, {
      name: '<?php echo $items['unidad']; ?>',
      data: [<?php echo implode(',', $items['valor']); ?>]
    }, {
      name: 'Minimo',
      data: [<?php echo implode(',', array_fill(0, $valorLength, $items['min'])); ?>]
    }]
  });
<?php } ?>
});
</script>
<script src="<?php echo base_url('contents/js/highcharts.js'); ?>"></script>
<script src="<?php echo base_url('contents/js/gray.js'); ?>"></script>
<div class="row">
</div> 
</div>