<style>
body {background: #2980b9}
.container {background: #2980b9}
</style>
<div class="container">
<h1 class="text-center panel panel-primary">SISTEMA SPC - CORPESCA</h1>
<div class="row">
<?php
if ($sectores) {
  for ($i = 0, $sectoresLength = sizeof($sectores); $i < $sectoresLength; ++$i) {
?>
<div class="col-md-5 panel panel-primary text-center thumbnail"><h2><a href="<?php echo $enlace_base_planilla . '/' . $sectores[$i]->url; ?>"><?php echo $sectores[$i]->nombre; ?></a></h2></div>
<div class="col-md-1"></div>
<?php }} ?>
</div>
<div class="row">
<div class="col-md-4"></div>
<div class="col-md-3 text-center"><a href="<?php echo base_url('graficos'); ?>"><img width="230" src="<?php echo base_url('contents/img/grafico.png'); ?>"></a></div>
<div class="col-md-6"></div>
</div>
</div>