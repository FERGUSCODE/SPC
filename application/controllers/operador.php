<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Operador extends CI_Controller {
  public function __construct(){
    parent::__construct();
    $this->load->helper('url');

    // session
    $this->load->library('session');
    if (!$this->session->userdata('id')) {
      redirect('/');
    }

    $this->load->model('planillas');
    $this->load->model('planilla_datos');
  }

  public function index() {
    // En caso de administrador, usar menú inicial
    if ($this->session->userdata('isAdmin')) {
      redirect('/');
    }

    // Obtener datos de sectores permitidos segun el usuario
    $sector_access_data = $this->planillas->get_sector_acceso($this->session->userdata('id'));

    // Buffer de botones para agregar datos
    $botones = array();

    for ($i = 0, $datosLength = sizeof($sector_access_data); $i < $datosLength; ++$i) {
      // Elegir título según tipo
      switch ($sector_access_data[$i]->url) {
        case 'cocedor':
          $titulo = 'Agregar temperaturas';
          break;
        case 'prensa':
          $titulo = 'Agregar Presión hidráulica (BAR)';
          break;
        case 'evaporador':
          $titulo = 'Agregar % Solido Concentrado';
          break;
        case 'secador':
          $titulo = 'Agregar % Fluido Termico';
          break;
      }

      // Ponerlo en buffer
      array_push($botones, array(
        'titulo' => $titulo, 
        'fecha' => $sector_access_data[$i]->fecha, 
        'enlace_agregar_dato' => base_url('operador/' . $sector_access_data[$i]->url . '/agregar')
      ));
    }

    $this->load->view('header');
    $this->load->view('operador/index', array(
      'enlace_salir' => base_url('logout'),
      'titulo' => 'Operador',
      'botones' => $botones
    ));
  }

  public function agregar($sector_url) {
    // Buffer para almacenar mensaje
    $msg = '';

    $sector_data = $this->planillas->get_sector_data_by_url($sector_url);

    $inputs = $this->input->post('value');
    if ($inputs) {
      // Buffer de los datos para insertar
      $datos = array();


      // Loop de los inputs
      foreach ($inputs as $input_maquina => $input_valor) {
        // Si el valor de input no es vacio y es numerico
        if (!empty($input_valor) && is_numeric($input_valor)) {
          // Agregar al buffer
          array_push($datos, array(
            'sector_id' => $sector_data->id, 
            'maquina_id' => $input_maquina, 
            'valor' => $input_valor
          ));
        }
      }

      // Insertar datos en conjunto
      if ('access_error' != $this->planilla_datos->insert($this->session->userdata('id'), $datos)) {
        // Configurar mensaje y redireccionar
        $msg = 'La información ha sido ingresada correctamente';
      } else {
        $msg = '¡Detectamos intento de acceso de datos no correspondiente!';
      }
    }

    switch ($sector_url) {
      case 'cocedor':
        $titulo = 'Agregar Temperatura';
        break;
      case 'prensa':
        $titulo = 'Agregar Presión hidráulica (BAR)';
        break;
      case 'evaporador':
        $titulo = 'Agregar % Solido Concentrado';
        break;
      case 'secador':
        $titulo = 'Agregar % Fluido Termico';
        break;
    }

    // Buffer de inputs
    $inputs = array();
    for ($i = 0, 
         $maquinas = $this->planillas->get_maquinas_by_sector_id($sector_data->id), 
         $maquinasLength = sizeof($maquinas); $i < $maquinasLength; ++$i) {
      array_push($inputs, array(
        'nombre' => $maquinas[$i]->nombre, 
        'maquina' => $maquinas[$i]->id
      ));
    }

    // Mostrar interfaz
    $this->load->view('header');
    if ($this->session->userdata('isAdmin')) {
      $this->load->view('header-admin', array(
        'enlace_base_planilla' => base_url('planilla/'), 
        'sectores' => $this->planillas->get_all_sector(), 
        'nombre' => $this->session->userdata('nombre'),
        'msg' => $msg
      ));
    }
    $this->load->view('operador/modificar', array(
      'titulo' => $titulo, 
      'actionURL' => base_url('/operador/'. $sector_url .'/agregar'),
      'inputs' => $inputs
    ));
  }

  public function editar($sector_url, $dato_id) {
    // Prevenir ser accedido por no administradores
    if (!$this->session->userdata('isAdmin')) {
      redirect('/');
    }

    // Buffer de mensaje
    $msg = '';

    // Ver si hay envío de datos
    $inputs = $this->input->post('value');
    if ($inputs && !empty($inputs)) {
      $maquina_id = current(array_keys($inputs));

      // Si el valor de input no es vacio y es numerico
      if (!empty($inputs[$maquina_id]) && is_numeric($inputs[$maquina_id])) {
        // Actualizar
        $this->planilla_datos->update($id, $maquina_id, $inputs[$maquina_id]);

        // Redireccionar
        $this->session->set_flashdata('msg','El registro ha sido modificado correctamente');
        redirect('planilla/' . $sector_url . '/view/' . $planilla_id);
      } else {
        // Mostrar error
        $msg = 'El dato ingresado es incorrecto';
      }
    }

    switch ($sector_url) {
      case 'cocedor':
        $titulo = 'Editar Temperatura';
        break;
      case 'prensa':
        $titulo = 'Editar Presión hidráulica (BAR)';
        break;
      case 'evaporador':
        $titulo = 'Editar % Solido Concentrado';
        break;
      case 'secador':
        $titulo = 'Editar % Fluido Termico';
        break;
    }

    // Obtener datos de la maquina
    $maquina_data = $this->planillas->get_maquina_by_id(
      $this->planilla_datos->get_by_id($dato_id)->maquina_id
    );

    // Mostrar interfaz
    $this->load->view('header');
    $this->load->view('header-admin', array(
      'enlace_base_planilla' => base_url('planilla/'), 
      'sectores' => $this->planillas->get_all_sector(), 
      'nombre' => $this->session->userdata('nombre'), 
      'msg' => $msg
    ));
    $this->load->view('operador/modificar', array(
      'titulo' => $titulo, 
      'actionURL' => base_url('/operador/'. $sector_url .'/editar/' . $dato_id),
      'inputs' => array(
        array(
          'nombre' => $maquina_data->nombre, 
          'maquina' => $maquina_id
        )
      )
    ));
  }

  public function eliminar($sector_url, $dato_id) {
    // Prevenir ser accedido por no administradores
    if (!$this->session->userdata('isAdmin')) {
      redirect('/');
    }

    $this->planilla_datos->delete($dato_id);
    redirect('planilla/' . $sector_url);
  }

  public function grafico($sector_url, $planilla_id) {
    // Buffer de mensaje
    $msg = '';

    switch ($sector_url) {
      case 'cocedor':
        $titulo = 'Temperatura de Licor';
        break;
      case 'prensa':
        $titulo = 'Presión hidráulica (BAR)';
        break;
      case 'evaporador':
        $titulo = '% Solido Concentrado';
        break;
      case 'secador':
        $titulo = '% Fluido Termico';
        break;
    }

    $planilla_data = $this->planillas->get_by_id($planilla_id);
    $planilla_datos = $this->planilla_datos->get_by_planilla($planilla_id);
    $titulo .= ' ' . $planilla_data->fecha;

    $datos = array();
    for ($i = 0, $planillaDatosLength = sizeof($planilla_datos), $maquinaId; $i < $planillaDatosLength; ++$i) {
      $maquinaId = $planilla_datos[$i]->maquina_id;

      if (!isset($datos[$maquinaId]['min'])) {
        $maquina_dato = $this->planillas->get_maquina_by_id($maquinaId);
        $datos[$maquinaId]['min'] = $maquina_dato->min;
        $datos[$maquinaId]['max'] = $maquina_dato->max;
        $datos[$maquinaId]['unidad'] = $maquina_dato->unidad;
      }

      array_push($datos[$maquinaId]['tiempo'], $datos[$planilla_datos[$i]]->tiempo);
      array_push($datos[$maquinaId]['valor'], $datos[$planilla_datos[$i]]->valor);
    }

    $this->load->view('header');
    if ($this->session->userdata('isAdmin')) {
      $this->load->view('header-admin', array(
        'enlace_base_planilla' => base_url('planilla/'), 
        'sectores' => $this->planillas->get_all_sector(), 
        'nombre' => $this->session->userdata('nombre'), 
        'msg' => $msg
      ));
    }
    $this->load->view('/operador/grafico', array(
      'titulo' => $titulo,
      'datos' => $datos,
    ));
  }
    
  public function pdf($sector_url, $planilla_id) {
    // Prevenir ser accedido por no administradores
    if (!$this->session->userdata('isAdmin')) {
      redirect('/');
    }

/*
    $query = $this->planillas->get_by_id($planilla_id);
    $query2 = $this->planilla_datos->get_all($id);
    $query3 = $this->planilla_datos->get($id);
    error_reporting(0);
    include(dirname(__FILE__) . '/../libraries/MPDF54/mpdf.php');
    $tabla = '
<table width="700" border="1">
<tr>
<th rowspan="3"><img width="50" src="/spc/contents/img/logo.jpg"></img></th>
<td colspan="2"><h4><center>SPC - Temperatura - Cocedores</h4></td>
</tr>
<tr>
<th>Fecha</th>
<th>Monitor</th>
</tr>
<tr>
<td><center>' . $query->fecha . '</center></td>
<td><center>' . $query->monitor . '</center></td>
</tr>
</table><br>';
    $tabla.= '
<table width="700" align="center" border="1">
<tr>
<th>Hora de Muestra</th>
<th>Cocedor Nº1</th>
<th>Cocedor Nº3</th>
<th>Cocedor Nº4</th>
</tr>';
    foreach($query2 as $list){
    $tabla.='
<tr>
<td><center>'.$list->cocedor_inicio.'</center></td>
<td><center>'.$list->cocedor_n1.'º</center></td>
<td><center>'.$list->cocedor_n3.'º</center></td>
<td><center>'.$list->cocedor_n4.'º</center></td>
</tr>';
    }
    $tabla.='</table>';
    $mpdf= new mPDF();
    $mpdf->WriteHTML($tabla);
    $mpdf->Output($query->fecha.'-Cocedores.pdf','I');
*/
  }
}