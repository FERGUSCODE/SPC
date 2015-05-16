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
    $successMsg = '';
    $warningMsg = '';

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
      if ($this->planilla_datos->insert($this->session->userdata('id'), $datos)) {
        // Configurar mensaje y redireccionar
        $successMsg = 'La información ha sido ingresada correctamente';
      } else {
        $warningMsg = '¡Detectamos intento de acceso de datos no correspondiente!';
      }
    }

    // Buffer de inputs
    $inputs = array();
    for ($i = 0, 
         $maquinas = $this->planillas->get_maquinas_by_sector_id($sector_data->id), 
         $maquinasLength = sizeof($maquinas); $i < $maquinasLength; ++$i) {
      array_push($inputs, array(
        'nombre' => $maquinas[$i]->nombre, 
        'maquina' => $maquinas[$i]->id, 
        'valor' => 0
      ));
    }

    // Mostrar interfaz
    $this->load->view('header');
    if ($this->session->userdata('isAdmin')) {
      $this->load->view('header-admin', array(
        'enlace_base_planilla' => base_url('planilla/'), 
        'sectores' => $this->planillas->get_all_sector(), 
        'nombre' => $this->session->userdata('nombre'),
        'successMsg' => $successMsg, 
        'warningMsg' => $warningMsg
      ));
    }
    $this->load->view('operador/modificar', array(
      'titulo' => 'Agregar ' . $sector_data->medida, 
      'actionURL' => base_url('/operador/'. $sector_url .'/agregar'),
      'inputs' => $inputs
    ));
  }

  public function editar($sector_url, $dato_id = 0) {
    // Prevenir ser accedido por no administradores
    if (!$this->session->userdata('isAdmin')) {
      redirect('/');
    }

    // Evitar de haber ingresado sin el id del dato
    if (!$dato_id) {
      redirect('/planilla/' . $sector_url);
    }

    $sector_data = $this->planillas->get_sector_data_by_url($sector_url);

    // Buffer de mensaje
    $successMsg = '';
    $warningMsg = '';

    // Ver si hay envío de datos
    $inputs = $this->input->post('value');
    if ($inputs && !empty($inputs)) {
      $maquina_id = current(array_keys($inputs));

      // Si el valor de input no es vacio y es numerico
      if (!empty($inputs[$maquina_id]) && is_numeric($inputs[$maquina_id])) {
        // Actualizar
        if ($this->planilla_datos->update($dato_id, $maquina_id, $inputs[$maquina_id])) {
          $this->session->set_flashdata('successMsg','El registro ha sido modificado correctamente');
        } else {
          $this->session->set_flashdata('warningMsg','El registro no ha podido ser modificado');
        }

        // Redireccionar
        redirect('/planilla/' . $sector_url . '/ver/' . $planilla_id);
      } else {
        // Mostrar error
        $warningMsg = 'El dato ingresado es incorrecto';
      }
    }

    // Obtener datos de la maquina
    $maquina_dato = $this->planilla_datos->get_by_id($dato_id);
    $maquina_id = $maquina_dato->maquina_id;
    $maquina_data = $this->planillas->get_maquina_by_id($maquina_id);

    // Mostrar interfaz
    $this->load->view('header');
    $this->load->view('header-admin', array(
      'enlace_base_planilla' => base_url('planilla/'), 
      'sectores' => $this->planillas->get_all_sector(), 
      'nombre' => $this->session->userdata('nombre'), 
      'successMsg' => $successMsg,
      'warningMsg' => $warningMsg
    ));
    $this->load->view('operador/modificar', array(
      'titulo' => 'Editar ' . $sector_data->medida, 
      'actionURL' => base_url('/operador/'. $sector_url .'/editar/' . $dato_id),
      'inputs' => array(
        array(
          'nombre' => $maquina_data->nombre, 
          'maquina' => $maquina_id, 
          'valor' => $maquina_dato->valor, 
        )
      )
    ));
  }

  public function eliminar($sector_url, $dato_id = 0) {
    // Prevenir ser accedido por no administradores
    if (!$this->session->userdata('isAdmin')) {
      redirect('/');
    }

    // Evitar de haber ingresado sin el id del dato
    if ($dato_id) {
      $this->planilla_datos->delete($dato_id);
    }

    redirect('/planilla/' . $sector_url);
  }

  public function grafico($sector_url, $planilla_id = 0) {
    // Evitar de haber ingresado sin el id de la planilla
    if (!$planilla_id) {
      redirect('/');
    }

    // Buffer de mensaje
    $msg = '';

    $sector_data = $this->planillas->get_sector_data_by_url($sector_url);

    $planilla_data = $this->planillas->get_by_id($planilla_id);
    $planilla_datos = $this->planilla_datos->get_by_planilla($planilla_id);

    $datos = array();
    for ($i = 0, $planillaDatosLength = sizeof($planilla_datos), $maquinaId; $i < $planillaDatosLength; ++$i) {
      $maquinaId = $planilla_datos[$i]->maquina_id;

      if (!isset($datos[$maquinaId]['min'])) {
        $maquina_dato = $this->planillas->get_maquina_by_id($maquinaId);
        $datos[$maquinaId]['nombre'] = $maquina_dato->nombre;
        $datos[$maquinaId]['min'] = $maquina_dato->min;
        $datos[$maquinaId]['max'] = $maquina_dato->max;
        $datos[$maquinaId]['unidad'] = $maquina_dato->unidad;
        $datos[$maquinaId]['tiempo'] = array();
        $datos[$maquinaId]['valor'] = array();
      }

      array_push($datos[$maquinaId]['tiempo'], $planilla_datos[$i]->tiempo);
      array_push($datos[$maquinaId]['valor'], $planilla_datos[$i]->valor);
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
      'titulo' => $sector_data->medida, 
      'fecha' => $planilla_data->fecha,
      'datos' => $datos,
    ));
  }
}