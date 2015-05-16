<div class="container">
<h1><?php echo $titulo; ?> <a class="btn btn-default pull-right" href="<?php echo base_url('logout'); ?>">Salir</a></h1>
<?php
for ($i = 0, $botonesLength = sizeof($botones); $i < $botonesLength; ++$i) {
  $currentBoton = $botones[$i];
  echo '<a class="btn btn-default btn-lg btn-block" href="' . base_url($currentBoton['enlace_agregar_dato']) . '">' . $currentBoton['titulo'] . '<br>' . $currentBoton['fecha'] . '</a>';
}
?>