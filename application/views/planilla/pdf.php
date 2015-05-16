<!DOCTYPE html>
<meta charset="utf-8">
<link rel="stylesheet" href="<?php echo base_url('contents/css/bootstrap_celeste.css'); ?>">
<div class="container">
<h1 class="text-center"><img width="100" height="100" src="<?php echo base_url('contents/img/logo.jpg'); ?>"> CORPESCA S.A - SPC</h1>
<h2><?php echo $titulo . ' ' . $datos->fecha; ?></h2>
<table class="table table-striped table-bordered">
<thead>
<tr>
<th class="text-center">Monitores
</tr>
</thead>
<tbody>
<td><?php echo implode(', ', $monitores); ?></td>
</tr>
</tbody>
</table>
<div class="row">
<?php
if ($contenido){
  $maquinaLength = sizeof($contenido);
  $columnSize = (int) (12 / $maquinaLength);

  if ($columnSize < 3) {
    $columnSize = 3;
  }

  foreach ($contenido as $items) {
?>
<div class="col-md-<?php echo $columnSize; ?>">
<div class="panel panel-default">
<div class="panel-heading"><?php echo $items['nombre']; ?></div>
<table class="table table-striped table-bordered">
<thead>
<tr>
<th class="col-md-2 text-center">Hora
<th class="text-center">Valor
</tr>
</thead>
<tbody>
<?php foreach ($items['datos'] as $dato_id => $dato) { ?>
<tr>
<td class="text-center"><?php echo $dato['tiempo']; ?>
<td class="text-center"><?php echo $dato['valor'] . $items['unidad']; ?>
</tr>
<?php } ?>
</tbody>
</table>
</div>
</div>
<?php
  }
} else {
  echo '<div class="col-md-12">No hay registros ingresados en este día.</div>';
}
?>
</div>
</div>