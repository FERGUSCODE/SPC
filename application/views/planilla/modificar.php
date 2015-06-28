<div class="container">
<h1><?php echo $titulo; ?></h1>
<form class="row" action="<?php echo current_url(); ?>" method="post" autocomplete="off">
<div class="col-md-3 form-group"><h2>Fecha</h2><input type="date" id="fecha" class="input-lg form-control" value="<?php echo $fecha; ?>" name="fecha" required></div>
<div class="col-md-9 form-group">
<h2>Monitores</h2>
<select multiple name="monitor[]" class="form-control" required>
<?php
for ($i = 0, $usuariosLength = sizeof($usuarios), $currentUsuario; $i < $usuariosLength; ++$i) {
  $currentUsuario = $usuarios[$i];
  echo '<option value="' . $currentUsuario['id'] . '"' . ($currentUsuario['seleccionado'] ? ' selected' : '') . '> ' . $currentUsuario['nombre'] . '</option>';
}
?>
</select>
</div>
<div class="col-md-12"><input class="btn btn-primary btn-lg btn-block" type="submit" value="<?php echo $submit_button_text; ?>"></div>
</form>
</div>