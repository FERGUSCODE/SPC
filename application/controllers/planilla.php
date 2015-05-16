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
    if (!$this->session->userdata('isAdmin')) {
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

    $sector_data = $this->planillas->get_sector_data_by_url($sector_url);

    if ($sector_data) {
      $this->load->view('header');
      $this->load->view('header-admin', array(
        'enlace_base_planilla' => base_url('planilla/'), 
        'sectores' => $this->planillas->get_all_sector(), 
        'nombre' => $this->session->userdata('nombre')
      ));
      $this->load->view('planilla/index', array(
        'titulo' => 'Modulo de ' . $sector_data->nombre,
        'enlace_agregar' => base_url('planilla/' . $sector_url . '/agregar'), 
        'enlace_base_ver' => base_url('planilla/' . $sector_url . '/ver/'), 
        'enlace_base_editar' => base_url('planilla/' . $sector_url . '/editar/'), 
        'planillas' => $this->planillas->get_by_sector_url($sector_url)
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

    $sector_data = $this->planillas->get_sector_data_by_url($sector_url);

    $this->load->view('header');
    $this->load->view('header-admin', array(
      'enlace_base_planilla' => base_url('planilla/'), 
      'sectores' => $this->planillas->get_all_sector(), 
      'nombre' => $this->session->userdata('nombre')
    ));
    $this->load->view('planilla/ver', array(
      'titulo' => 'Planilla - ' . $sector_data->nombre,
      'datos' => $this->planillas->get_by_id($planilla_id), 
      'contenido' => $this->planilla_datos->get_by_planilla($planilla_id), 
      'enlace_agregar_dato' => base_url('operador/' . $sector_url . '/agregar/'), 
      'enlace_base_exportar_dato' => base_url('operador/' . $sector_url . '/pdf/'), 
      'enlace_base_grafico_dato' => base_url('operador/' . $sector_url . '/grafico/')
    ));
  }

  public function agregar($sector_url) {
    $sector_data = $this->planillas->get_sector_data_by_url($sector_url);

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
      'enlace_base_planilla' => base_url('planilla/'), 
      'sectores' => $this->planillas->get_all_sector(), 
      'nombre' => $this->session->userdata('nombre')
    ));
    $this->load->view('planilla/modificar', array(
      'actionURL' => base_url('planilla/' . $sector_url . '/agregar'),
      'titulo' => 'Crear planilla para ' . $sector_data->nombre,
      'fecha' => date('Y-m-d'), 
      'usuarios' => $this->usuario->get_all(), 
      'submit_button_text' => 'Crear Planilla'
    ));
  }

  public function editar($sector_url, $planilla_id = 0) {
    // Evitar de haber ingresado sin el id de la planilla
    if (!$planilla_id) {
      redirect('/planilla/' . $sector_url);
    }

    $sector_data = $this->planillas->get_sector_data_by_url($sector_url);

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
      'enlace_base_planilla' => base_url('planilla/'), 
      'sectores' => $this->planillas->get_all_sector(), 
      'nombre' => $this->session->userdata('nombre')
    ));
    $this->load->view('planilla/modificar',array(
      'actionURL' => base_url('planilla/' . $sector_url . '/editar/' . $planilla_id),
      'titulo' => 'Editar planilla de ' . $sector_data->nombre,
      'fecha' => date('Y-m-d'), 
      'usuarios' => $this->usuario->get_all(), 
      'submit_button_text' => 'Editar Planilla'
    ));
  }
}