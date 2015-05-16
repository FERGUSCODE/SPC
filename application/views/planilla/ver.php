<div class="container">
<h1><?php echo $titulo . ' ' . $datos->fecha; ?></h1>
<table class="table table-striped table-bordered">
<thead>
<tr>
<th class="text-center">Monitores
<th class="col-md-1 text-center">Agregar Registro
<th class="col-md-1 text-center">Exportar
<th class="col-md-1 text-center">Gráfico
</tr>
</thead>
<tbody>
<td><?php echo implode(', ', $monitores); ?></td>
<td class="col-md-1 text-center"><a href="<?php echo $enlace_agregar_dato; ?>" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-plus"></span> Insertar Registro</a>
<td class="col-md-1 text-center"><a href="<?php echo $enlace_base_exportar_dato . '/' . $datos->id; ?>" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-file"></span> PDF</a>
<td class="col-md-1 text-center"><a href="<?php echo $enlace_base_grafico_dato . '/' . $datos->id; ?>" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-stats"></span> Ver</a>
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
<th class="col-md-1 text-center">Editar
<th class="col-md-1 text-center">Eliminar
</tr>
</thead>
<tbody>
<?php foreach ($items['datos'] as $dato_id => $dato) { ?>
<tr>
<td class="text-center"><?php echo $dato['tiempo']; ?>
<td class="text-center"><?php echo $dato['valor'] . $items['unidad']; ?>
<td class="text-center"><a href="<?php echo $enlace_base_editar_dato . '/' . $dato_id; ?>" class="glyphicon glyphicon-pencil"></a></td>
<td class="text-center"><a onclick="return confirm('esta seguro de borrar esta hora?');" href="<?php echo $enlace_base_eliminar_dato . '/' . $dato_id; ?>" class="glyphicon glyphicon-trash"></a></td></tr>
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