<style>
body {background: #ecf0f1}
.container {background: #ecf0f1}
</style>
<div align="center" class="container">
<a href="<?php echo $enlace_salir; ?>"><img src="<?php echo base_url('contents/img/salir.png'); ?>" width="150"></img></a>
<h1 class="text-center panel panel-primary"><?php echo $titulo; ?></h1>
<?php
for ($i = 0, $botonesLength = sizeof($botones); $i < $botonesLength; ++$i) {
  $currentBoton = $botones[$i];
  echo '<h2><a href="' . $currentBoton['enlace_agregar_dato'] . '"><span class="col-md-3"></span><span class="text-center col-md-6 alert alert-info">' . $currentBoton['titulo'] . '<br>' . $currentBoton['fecha'] . '</span></a></h2>';
}
?>