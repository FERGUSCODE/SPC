<nav class="navbar navbar-inverse" role="navigation">
<div class="container-fluid">
<div class="navbar-header">
<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse"><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button>
<a class="navbar-brand">Corpesca S.A</a>
</div>
<div class="collapse navbar-collapse">
<ul class="nav navbar-nav">
<li><a href="<?php echo base_url(); ?>">Inicio</a>
<?php
if ($sectores) {
  for ($i = 0, $sectoresLength = sizeof($sectores); $i < $sectoresLength; ++$i) {
?>
<li><a href="<?php echo base_url($enlace_base_planilla . $sectores[$i]->url); ?>"><?php echo $sectores[$i]->nombre; ?></a>
<?php }} ?>
</ul>
<ul class="nav navbar-nav navbar-right">
<li><a>Usuario: <?php echo $nombre;?></a>
<li><a href="<?php echo base_url('logout'); ?>">Salir</a>
</ul>
</div>
</div>
</nav>
<?php 
$successMsg = isset($successMsg) ? $successMsg : $this->session->flashdata('successMsg');
if ($successMsg) {
  echo '<div class="alert alert-success">' . $successMsg . '</div>';
}

$warningMsg = isset($warningMsg) ? $warningMsg : $this->session->flashdata('warningMsg');
if ($warningMsg) {
  echo '<div class="alert alert-warning">' . $warningMsg . '</div>';
}
?>