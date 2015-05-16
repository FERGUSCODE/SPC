<div class="container">
<h1>SISTEMA SPC - CORPESCA</h1>
<div class="row">
<?php
if ($sectores) {
  for ($i = 0, $sectoresLength = sizeof($sectores); $i < $sectoresLength; ++$i) {
?>
<div class="col-md-3"><a class="btn btn-default btn-lg btn-block" href="<?php echo $enlace_base_planilla . '/' . $sectores[$i]->url; ?>"><?php echo $sectores[$i]->nombre; ?></a></div>
<?php }} ?>
</div>
<div class="text-center"><a href="<?php echo base_url('graficos'); ?>"><img width="230" height="230" src="<?php echo base_url('contents/img/grafico.png'); ?>"></a></div>
</div>