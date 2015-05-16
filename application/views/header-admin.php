<script src="<?php echo base_url('contents/js/jquery.min.js'); ?>"></script>
<script src="<?php echo base_url('contents/js/jquery.dataTables.min.js'); ?>"> </script>
<script src="<?php echo base_url('contents/js/dataTables.bootstrap.js'); ?>"> </script>
<script src="<?php echo base_url('contents/js/highcharts.js'); ?>"></script>
<script src="<?php echo base_url('contents/js/bootstrap.min.js'); ?>"></script>
<script>
$(document).ready(function() {
  setTimeout(function() {
    $('.content').fadeOut(1500);
  },5000);
});
</script>
<div class="navbar navbar-static-top navbar-inverse" role="navigation">
<div class="navbar-header">
<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse"><span class="icon-bar"></span></button>
<a class="navbar-brand" href="/">Corpesca S.A</a>
</div>
<div class="collapse navbar-collapse">
<ul class="nav navbar-nav">
<li><a href="<?php echo base_url(); ?>">Inicio</a>
<?php
if ($sectores) {
  for ($i = 0, $sectoresLength = sizeof($sectores); $i < $sectoresLength; ++$i) {
?>
<li><a href="<?php echo $enlace_base_planilla . '/' . $sectores[$i]->url; ?>"><?php echo $sectores[$i]->nombre; ?></a>
<?php }} ?>
</ul>
<div class="container">
<ul class="nav navbar-nav navbar-right">
<li><a class="navbar-static-top">Usuario: <?php echo $nombre;?></a></li>
<li><a class="navbar-fixed-top" href="<?php echo base_url('logout'); ?>">Salir</a></li>
</ul>
</div>
</div>
</div>
<?php 
$successMsg = isset($successMsg) ? $successMsg : $this->session->flashdata('successMsg');
if ($successMsg) {
  echo '<div class="content alert alert-success">' . $successMsg . '</div>';
}

$warningMsg = isset($warningMsg) ? $warningMsg : $this->session->flashdata('warningMsg');
if ($warningMsg) {
  echo '<div class="content alert alert-warning">' . $warningMsg . '</div>';
}
?>