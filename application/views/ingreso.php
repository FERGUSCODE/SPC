<?php
if (isset($msg)) {
  echo '<div class="alert alert-warning" role="alert">' . $msg . '</div>';
}
?>
<div class="container">
<h1 class="text-center"><img width="100" height="100" src="<?php echo base_url('contents/img/logo.jpg'); ?>"> CORPESCA S.A - SPC</h1>
<form action="<?php echo $actionURL; ?>" method="post" autocomplete="off">
<div class="form-group">
<label class="sr-only" for="usuario">Usuario</label>
<select id="usuario" name="usuario" class="form-control input-lg" required>
<?php
foreach ($usuarios as $usuario) {
  echo '<option value="' . $usuario->usuario . '">' . $usuario->nombre . '</option>';
}
?>
</select>
</div>
<div class="form-group">
<label class="sr-only" for="contrasena">Contraseña</label>
<input type="password" id="contrasena" name="contrasena" class="form-control input-lg" placeholder="Ingrese su Contraseña" required>
</div>
<input class="btn btn-lg btn-primary btn-block" type="submit" value="Ingresar al Sistema">
</form>