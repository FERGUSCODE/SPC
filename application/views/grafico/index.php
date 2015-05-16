<!DOCTYPE html>
<meta charset="utf-8">
<title>GRAFICOS</title>
<link rel="stylesheet" href="<?php echo base_url('contents/css/bootstrap_celeste.css'); ?>">
<script src="<?php echo base_url('contents/js/jquery.min.js'); ?>"></script>
<?php
$cocedor_ids = array(1, 3, 4);
@$plantilla_id = $cocedor[0]->id;

for ($x = 0, $cocedor_ids_length = sizeof($cocedor_ids), $current; $x < $cocedor_ids_length; ++$x) {
    $current = $cocedor_ids[$x];
    $cocedores = $this->cocedor->get_all($plantilla_id, $current);
    $ccontador = sizeof($cocedores);
?>
<script>
$(function () {
$('#container<?php echo $current; ?>').highcharts({
title: {
text: 'Temperatura de Licor - Cocedor Nº<?php echo $current; ?>',
x: -20 //center
},
xAxis: {
categories: [<?php 
foreach($cocedores as $c){
echo "'".$c->cocedor_inicio."',";
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
echo "98,";} ?>]
},

{
name: 'Medio',
data: [<?php for($i = 1 ; $i <= $ccontador; $i++){
echo "94,";} ?>]
},
{
name: 'Cº',
data: [<?php 
foreach($cocedores as $c){
echo $c->cocedor_valor.",";
}
?>]
}, {
name: 'Minimo',
data: [<?php for($i = 1 ; $i <= $ccontador; $i++){
echo "90,";} ?>]
}]
});
});
</script>
<?php } ?>
<script src="<?php echo base_url('contents/js/highcharts.js'); ?>"></script>
<script src="<?php echo base_url('contents/js/gray.js'); ?>"></script>
<script>
window.onload = function () {setTimeout(function () {location.replace('<?php echo base_url('graficos/prensa')'); ?>}, 7000)};
</script>
<div class="container-fluid">
<div class="row">
<div class="col-md-6" id="container1" style="min-width: 300x; height: 450px; margin: 0"></div>
<div class="col-md-6" id="container3" style="min-width: 300x; height: 440px; margin: 0"></div>
</div> 
</div> 
<div class="container-fluid">
<div class="row">
<div class="col-md-3" id="container99" style="min-width: 300x; height: 440px; margin: 0"></div> 
<div class="col-md-6" id="container4" style="min-width: 300x; height: 440px; margin: 0"></div>    
<div class="col-md-3" id="container99" style="min-width: 300x; height: 440px; margin: 0"></div>          
</div> 
</div>