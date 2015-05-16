<style>
#reloj {font-size:36px;font-weight:700;text-align:center;}
</style>
<p id="reloj">00:00:00</p>
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
<h1 class="form-signin-heading text-center h3"><?php echo $titulo; ?></h1>
<form name="form_reloj" class="form-signin" role="form" method="post" action="<?php echo $actionURL; ?>" autocomplete="off">
<table class="table">
<tr>
<?php
for ($i = 0, $inputsLength = sizeof($inputs); $i < $inputsLength; ++$i) {
  $currentInput = $inputs[$i];
  echo '<td class="text-center"><label><span class=".control-label">' . $currentInput['nombre'] . '</span><input type="number" step="0.1" class="input-lg" name="value[' . $currentInput['maquina'] . ']" value="' . $currentInput['valor'] . '"></label></td>';
}
?>
</tr>
</table>
<p><input class="btn btn-primary btn-lg btn-block" type="submit" value="AGREGAR">
</form>