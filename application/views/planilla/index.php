<h1 class="text-center"><?php echo $titulo; ?></h1>
<div class="container">
<a class="btn btn-default" href="<?php echo $enlace_agregar; ?>">Agregar planilla <span class="glyphicon glyphicon-plus"></span></a>
<table class="display table table-bordered" cellspacing="0" width="100%">
<thead>
<tr>
<th class="text-center">Fecha Creación</th>
<th class="text-center">Monitores</th>
<th class="text-center">Ver Planilla</th>
<th class="text-center">Editar</th>
</tr>
</thead>
<tbody>
<?php foreach($planillas as $planilla){ ?>
<tr>
<td height="1" class="text-center"><?php echo $planilla->fecha; ?></td>
<td class="text-center"><?php /*echo $planilla->monitores;*/ ?></td>
<td class="text-center"><a href="<?php echo $enlace_base_ver . '/' . $planilla->id; ?>" class="btn btn-xs btn-primary glyphicon glyphicon-search"></a></td>
<td class="text-center"><a href="<?php echo $enlace_base_editar . '/' . $planilla->id; ?>" class="glyphicon glyphicon-pencil"></a></td>
</tr>
<?php } ?>
</tbody>
</table>
</div>