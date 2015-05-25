<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Planilla extends CI_Controller {
  public function __construct(){
    parent::__construct();

    date_default_timezone_set('America/Santiago');

    $this->load->helper('url');

    // session
    $this->load->library('session');

    // Revisar si es administrador
    if (!$this->session->userdata('es_admin')) {
      // No es administrador, redireccionar a otra parte
      redirect('/');
    }

    $this->load->model('planillas');
    $this->load->model('planilla_datos');
    $this->load->model('usuario');
  }
  
  public function index($sector_url = '') {
    // Evitar de haber ingresado sin el url de la planilla
    if (empty($sector_url)) {
      redirect('/');
    }

    $planta_id = $this->session->userdata('planta_id');

    $sector_data = $this->planillas->get_sector_data_by_url($sector_url, $planta_id);
    $planillas = $this->planillas->get_by_sector_url($sector_url, $planta_id);

    $planilla_data = array();
    foreach ($planillas as $planilla) {
      $monitor_data = $this->planillas->get_usuarios_planilla($planilla->id);

      $monitores = array();
      for ($i = 0, $monitorDataLength = sizeof($monitor_data); $i < $monitorDataLength; ++$i) {
        $monitores[$monitor_data[$i]->id] = $monitor_data[$i]->nombre;
      }

      array_push($planilla_data, array(
        'id' => $planilla->id, 
        'fecha' => $planilla->fecha, 
        'monitores' => $monitores
      ));
    }

    if ($sector_data) {
      $this->load->view('header');
      $this->load->view('header-admin', array(
        'enlace_base_planilla' => 'planilla/', 
        'sectores' => $this->planillas->get_all_sector($planta_id), 
        'nombre' => $this->session->userdata('nombre')
      ));
      $this->load->view('planilla/index', array(
        'titulo' => 'Modulo de ' . $sector_data->nombre,
        'enlace_agregar' => 'planilla/' . $sector_url . '/agregar', 
        'enlace_base_ver' => 'planilla/' . $sector_url . '/ver/', 
        'enlace_base_editar' => 'planilla/' . $sector_url . '/editar/', 
        'planillas' => $planilla_data
      ));
    } else {
      show_404('planilla');
    }
  }

  public function ver($sector_url, $planilla_id = 0) {
    // Evitar de haber ingresado sin el id de la planilla
    if (!$planilla_id) {
      redirect('/planilla/' . $sector_url);
    }

    $planta_id = $this->session->userdata('planta_id');

    $sector_data = $this->planillas->get_sector_data_by_url($sector_url, $planta_id);
    $monitor_data = $this->planillas->get_usuarios_planilla($planilla_id);

    $monitores = array();
    for ($i = 0, $monitorDataLength = sizeof($monitor_data); $i < $monitorDataLength; ++$i) {
      $monitores[$monitor_data[$i]->id] = $monitor_data[$i]->nombre;
    }

    $planilla_datos = $this->planilla_datos->get_by_planilla($planilla_id);

    $datos = array();
    for ($i = 0, $planillaDatosLength = sizeof($planilla_datos), $maquinaId; $i < $planillaDatosLength; ++$i) {
      $maquinaId = $planilla_datos[$i]->maquina_id;

      if (!isset($datos[$maquinaId]['nombre'])) {
        $maquina_dato = $this->planillas->get_maquina_by_id($maquinaId);
        $datos[$maquinaId]['nombre'] = $maquina_dato->nombre;
        $datos[$maquinaId]['unidad'] = $maquina_dato->unidad;
        $datos[$maquinaId]['datos'] = array();
      }

      $datos[$maquinaId]['datos'][$planilla_datos[$i]->id]['tiempo'] = $planilla_datos[$i]->tiempo;
      $datos[$maquinaId]['datos'][$planilla_datos[$i]->id]['valor'] = $planilla_datos[$i]->valor;
    }

    $this->load->view('header');
    $this->load->view('header-admin', array(
      'enlace_base_planilla' => 'planilla/', 
      'sectores' => $this->planillas->get_all_sector($planta_id), 
      'nombre' => $this->session->userdata('nombre')
    ));
    $this->load->view('planilla/ver', array(
      'titulo' => 'Planilla - ' . $sector_data->nombre,
      'datos' => $this->planillas->get_by_id($planilla_id), 
      'monitores' => $monitores, 
      'contenido' => $datos, 
      'enlace_agregar_dato' => 'operador/' . $sector_url . '/agregar/', 
      'enlace_base_exportar_dato' => 'planilla/' . $sector_url . '/pdf/', 
      'enlace_base_grafico_dato' => 'operador/' . $sector_url . '/grafico/', 
      'enlace_base_editar_dato' => 'operador/' . $sector_url . '/editar/', 
      'enlace_base_eliminar_dato' => 'operador/' . $sector_url . '/eliminar/'
    ));
  }

  public function agregar($sector_url) {
    $planta_id = $this->session->userdata('planta_id');

    $sector_data = $this->planillas->get_sector_data_by_url($sector_url, $planta_id);

    // Ver si ha enviado algunos datos
    $fecha = $this->input->post('fecha');

    if ($fecha) {
      $monitores = $this->input->post('monitor');
      $query = $this->planillas->insert($sector_data->id, $fecha, $monitores);
      $this->session->set_flashdata('msg', 'La planilla ha sido creada correctamente');
      redirect('/planilla/' . $sector_url);
    }

    $this->load->view('header');
    $this->load->view('header-admin', array(
      'enlace_base_planilla' => 'planilla/', 
      'sectores' => $this->planillas->get_all_sector($planta_id), 
      'nombre' => $this->session->userdata('nombre')
    ));
    $this->load->view('planilla/modificar', array(
      'titulo' => 'Crear planilla para ' . $sector_data->nombre,
      'fecha' => date('Y-m-d'), 
      'usuarios' => $this->usuario->get_all($planta_id), 
      'submit_button_text' => 'Crear Planilla'
    ));
  }

  public function editar($sector_url, $planilla_id = 0) {
    // Evitar de haber ingresado sin el id de la planilla
    if (!$planilla_id) {
      redirect('/planilla/' . $sector_url);
    }

    $planilla_dato = $this->planillas->get_by_id($planilla_id);

    if ($planilla_dato->fecha != date('Y-m-d')) {
      redirect('/planilla/' . $sector_url);
    }

    $planta_id = $this->session->userdata('planta_id');

    $sector_data = $this->planillas->get_sector_data_by_url($sector_url, $planta_id);

    // Ver si ha enviado algunos datos
    $fecha = $this->input->post('fecha'); 
    if ($fecha) {
      $monitores = $this->input->post('monitor');

      if ($this->planillas->update($planilla_id, $sector_data->id, $fecha, $monitores)) {
        $this->session->set_flashdata('msg', 'La planilla ha sido modificada correctamente');
      }

      redirect('/planilla/' . $sector_url);
    }


    $this->load->view('header');
    $this->load->view('header-admin', array(
      'enlace_base_planilla' => 'planilla/', 
      'sectores' => $this->planillas->get_all_sector($planta_id), 
      'nombre' => $this->session->userdata('nombre')
    ));
    $this->load->view('planilla/modificar',array(
      'titulo' => 'Editar planilla de ' . $sector_data->nombre,
      'fecha' => $planilla_dato->fecha, 
      'usuarios' => $this->usuario->get_all($planta_id), 
      'submit_button_text' => 'Editar Planilla'
    ));
  }

  public function pdf($sector_url, $planilla_id = 0) {
    // Prevenir ser accedido por no administradores
    if (!$this->session->userdata('es_admin')) {
      redirect('/');
    }

    // Evitar de haber ingresado sin el id de la planilla
    if (!$planilla_id) {
      redirect('/planilla/' . $sector_url);
    }

    $planta_id = $this->session->userdata('planta_id');

    $sector_data = $this->planillas->get_sector_data_by_url($sector_url, $planta_id);
    $monitor_nombre = $this->planillas->get_usuarios_planilla($planilla_id);

    $monitores = array();
    for ($i = 0, $monitorDataLength = sizeof($monitor_nombre); $i < $monitorDataLength; ++$i) {
      array_push($monitores, $monitor_nombre[$i]->nombre);
    }

    $planilla_datos = $this->planilla_datos->get_by_planilla($planilla_id);

    $datos = array();
    for ($i = 0, $planillaDatosLength = sizeof($planilla_datos), $maquinaId; $i < $planillaDatosLength; ++$i) {
      $maquinaId = $planilla_datos[$i]->maquina_id;

      if (!isset($datos[$maquinaId]['nombre'])) {
        $maquina_dato = $this->planillas->get_maquina_by_id($maquinaId);
        $datos[$maquinaId]['nombre'] = $maquina_dato->nombre;
        $datos[$maquinaId]['unidad'] = $maquina_dato->unidad;
        $datos[$maquinaId]['datos'] = array();
      }

      $datos[$maquinaId]['datos'][$planilla_datos[$i]->id]['tiempo'] = $planilla_datos[$i]->tiempo;
      $datos[$maquinaId]['datos'][$planilla_datos[$i]->id]['valor'] = $planilla_datos[$i]->valor;
    }

    error_reporting(0);
    $this->load->library('MPDF54/MPDF');
    $this->mpdf->WriteHTML($this->load->view('planilla/pdf', array(
      'titulo' => 'Planilla - ' . $sector_data->nombre,
      'datos' => $this->planillas->get_by_id($planilla_id), 
      'monitores' => $monitores, 
      'contenido' => $datos
    ), true));
    $this->mpdf->Output($sector_data->nombre . '.pdf','I');
  }
}