<div class="container">
<h1><?php echo $titulo . ' ' . $datos->fecha; ?></h1>
<table class="table table-striped table-bordered">
<thead>
<tr>
<th class="text-center">Monitores</th>
<th class="col-md-1 text-center">Agregar Registro</th>
<th class="col-md-1 text-center">Exportar</th>
<th class="col-md-1 text-center">Gráfico</th>
</tr>
</thead>
<tbody>
<td><?php echo implode(', ', $monitores); ?></td>
<td class="col-md-1 text-center"><a href="<?php echo $enlace_agregar_dato; ?>" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-plus"></span> Insertar Registro</a></td>
<td class="col-md-1 text-center"><a href="<?php echo $enlace_base_exportar_dato . '/' . $datos->id; ?>" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-file"></span> PDF</a></td>
<td class="col-md-1 text-center"><a href="<?php echo $enlace_base_grafico_dato . '/' . $datos->id; ?>" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-stats"></span> Ver</a></td>
</tr>
</tbody>
</table>
<?php /*
<table class="table table-bordered">
<tr>
<th class="text-center">Hora-Inspección</th>
<th class="text-center">Cocedor Nº1</th>
<th class="text-center">Cocedor Nº3</th>
<th class="text-center">Cocedor Nº4</th>
<th class="text-center">Editar</th>
<th class="text-center">Eliminar</th>
</tr>
<?php
if ($contenido){
	foreach($contenido as $dato){
?>
<tr height="10">
<td class="text-center"><?php echo $dato->tiempo; ?></td>
<td class="text-center"><?php echo $dato->cocedor_n1; ?>º</td>
<td class="text-center"><?php echo $dato->cocedor_n3; ?>º</td>
<td class="text-center"><?php echo $dato->cocedor_n4; ?>º</td>
<td class="text-center"><a href="'.base_url("cocedors/edit/".$dato->id).'" class="glyphicon glyphicon-pencil"></a></td>
<td class="text-center"><a onclick="return confirm('esta seguro de borrar esta hora?');" href="<?php echo base_url('cocedors/delete/' . $dato->id); ?>" class="glyphicon glyphicon-trash"></a></td></tr>
<?php
	}
} else {
	echo '<h1 class="alert alert-danger">No hay registros ingresados en este día.</h1>';
}
?>
</tr>
</table>
</div>
*/ ?>