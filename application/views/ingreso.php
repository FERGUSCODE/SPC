<style>
body {background: #ecf0f1}
.container {background: white}
</style>
<?php
if (isset($msg)) {
  echo '<div class="alert alert-warning" role="alert">' . $msg . '</div>';
}
?>
<div class="container jumbotron">
<img width="100" height="100" src="<?php echo base_url('contents/img/logo.jpg'); ?>">
<form class="form-signin" role="form" method="post" action="<?php echo $actionURL; ?>">
<h1 class="form-signin-heading">CORPESCA S.A - SPC</h1>
<p><select class="form-control" name="usuario" required>
<?php
foreach ($usuarios as $usuario) {
  echo '<option value="' . $usuario->usuario . '">' . $usuario->nombre . '</option>';
}
?>
</select>
<p><input type="password" name="contrasena" class="form-control" placeholder="Ingrese su Contraseña" required>
<p><input class="btn btn-lg btn-primary btn-block" type="submit" value="Ingresar al Sistema">
</form>