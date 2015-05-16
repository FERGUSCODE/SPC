<div class="container">
<h1><?php echo $titulo; ?> <a class="btn btn-default pull-right" href="<?php echo base_url($enlace_agregar); ?>">Agregar planilla <span class="glyphicon glyphicon-plus"></span></a></h1>
<table class="table table-striped table-bordered table-responsive">
<thead>
<tr>
<th class="col-md-2 text-center">Fecha Creación</th>
<th class="text-center">Monitores</th>
<th class="col-md-2 text-center">Acción</th>
</tr>
</thead>
<tbody>
<?php
foreach($planillas as $planilla){
  $editable = $planilla['fecha'] == date('Y-m-d');
?>
<tr>
<td class="col-md-2 text-center"><?php echo $planilla['fecha']; ?></td>
<td class="text-center"><?php echo implode(', ', $planilla['monitores']); ?></td>
<td class="col-md-2 text-center"><a href="<?php echo base_url($enlace_base_ver . $planilla['id']); ?>" class="btn btn-default btn-sm"><span class="glyphicon glyphicon-eye-open"></span> Ver</a> <a href="<?php echo $editable ? base_url($enlace_base_editar . $planilla['id']) : ''; ?>" class="btn btn-default btn-sm<?php echo $editable ? '' : ' disabled'; ?>"><span class="glyphicon glyphicon-pencil"></span> Editar</a></td>
</tr>
<?php } ?>
</tbody>
</table>
</div>