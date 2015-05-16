<div class="container">
<form class="form-signin" role="form" method="post" action="<?php echo $actionURL; ?>">
<h2 class="form-signin-heading text-center"><?php echo $titulo; ?></h2><br>
<p><label>Fecha</label> <input type="date" class="form-control" value="<?php echo $fecha; ?>" name="fecha" required>
<h3>Monitores</h3>
<?php
for ($i = 0, $usuariosLength = sizeof($usuarios), $currentUsuario; $i < $usuariosLength; ++$i) {
  $currentUsuario = $usuarios[$i];
  echo '<label class="checkbox-inline"><input type="checkbox" name="monitor[]" value="' . $currentUsuario->id . '" required> ' . $currentUsuario->nombre . '</label>';
}
?>
<p><input class="btn btn-primary form-control" type="submit" value="<?php echo $submit_button_text; ?>">
</form>
</div>