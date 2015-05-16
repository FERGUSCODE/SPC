<div class="container">
<table border="1" class="table table-bordered table-responsive">
<tr><td colspan="5"><h1 class="text-center"><?php echo $titulo; ?></h1></td></tr>
<tr>
<th class="text-center">Fecha</th>
<th class="text-center">Monitores</th>
<th class="text-center">Agregar Registro</th>
<th class="text-center">Exportar</th>
<th class="text-center">Gráfico</th>
</tr>
<tr class="text-center"><td width="100"><?php echo $datos->fecha; ?></td>
<td width="150" height="40"><?php /*echo $datos->monitores;*/ ?></td>
<td width="70"><a href="<?php echo $enlace_agregar_dato; ?>" class="btn btn-default btn-sm text-center">Insertar Registro  <span class="glyphicon glyphicon-plus"></span></a></td>
<td width="70"><a href="<?php echo $enlace_base_exportar_dato . '/' . $datos->id; ?>" class="btn btn-danger btn-sm text-center glyphicon glyphicon-file"><b> PDF</b></a></td>
<td width="70"><a href="<?php echo $enlace_base_grafico_dato . '/' . $datos->id; ?>" class="btn btn-success btn-sm text-center glyphicon glyphicon-stats"><b> Ver</b></a></td>
</tr>
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