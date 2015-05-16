<div class="container">
<h1><?php echo $titulo; ?></h1>
<p id="reloj" class="h2">00:00:00</p>
<script>
setInterval(function () {
  var tiempo = new Date();
  var hora = tiempo.getHours();
  var minuto = tiempo.getMinutes();
  var segundo = tiempo.getSeconds();

  var pad = '0';

  document.getElementById('reloj').innerHTML = (pad + hora).slice(-2) + ':' + (pad + minuto).slice(-2) + ':' + (pad + segundo).slice(-2);
}, 1000);
</script>
<form class="row" action="<?php echo $actionURL; ?>" method="post" autocomplete="off">
<?php
if (!empty($inputs)) {
  for ($i = 0, $inputsLength = sizeof($inputs), $row = (int) (12 / $inputsLength); $i < $inputsLength; ++$i) {
    $currentInput = $inputs[$i];
    echo '<div class="col-md-' . $row . ' form-group text-center"><h2>' . $currentInput['nombre'] . '</h2><input type="number" step="0.1" class="input-lg form-control" name="value[' . $currentInput['maquina'] . ']" value="' . $currentInput['valor'] . '"></div>';
  }
} else {
  echo '<div class="col-md-12"><p class="alert alert-warning">No hay maquinas.</p></div>';
}
?>
<div class="col-md-12"><input class="btn btn-primary btn-lg btn-block" type="submit" value="AGREGAR"></div>
</form>
</div>