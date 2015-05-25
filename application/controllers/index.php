<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Index extends CI_Controller {
  public function __construct(){
    parent::__construct();

    // Start session
    $this->load->library('session');

    $this->load->helper('url');
  }
  
  public function index(){
    $successMsg = '';
    $warningMsg = '';

    // Revisar si está ingresado
    if (!$this->session->userdata('id')) {
      // NO está ingresado, intentar ingresar
      $this->load->model('usuario');

      // Variable para almacenar datos que se pasará al view
      $data = array();

      // Captar valor de usuario
      $usuario = $this->input->post('usuario');

      // Revisar si hay envío de datos
      if ($usuario) {
        $contrasena = $this->input->post('contrasena');

        if (isset($usuario[0], $contrasena[0])) {
          $id = $this->usuario->validar_login($usuario, $contrasena);

          if ($id) {
            $userData = $this->usuario->get($id);

            // Guardar datos en sesión
            $this->session->set_userdata('id', $id);
            $this->session->set_userdata('nombre', $userData->nombre);
            $this->session->set_userdata('planta_id', $userData->planta_id);
            $this->session->set_userdata('es_admin', $userData->es_admin);

            redirect('');
          } else {
            // Si la combinación usuario/contraseña es erronea, mostrar error
            $data['msg'] = 'Usuario y Contraseña incorrecta';
          }
        } else {
          $data['msg'] = 'Los campos no pueden estar vacíos';
        }
      }

      $data['usuarios'] = $this->usuario->get_all();

      // Mostrar página
      $this->load->view('header');
      $this->load->view('ingreso', $data);
    } else {
      if ($this->session->userdata('es_admin')) {
        $this->load->model('planillas');

        $planta_id = $this->session->userdata('planta_id');

        $this->load->view('header');
        $this->load->view('header-admin', array(
          'enlace_base_planilla' => 'planilla/', 
          'sectores' => $this->planillas->get_all_sector($planta_id), 
          'nombre' => $this->session->userdata('nombre'),
          'successMsg' => $successMsg, 
          'warningMsg' => $warningMsg
        ));
        $this->load->view('portada', array(
          'enlace_base_planilla' => 'planilla/',
          'sectores' => $this->planillas->get_all_sector($planta_id)
        ));
      } else {
        redirect('operador');
      }
    }
  }

  public function logout(){
    $this->load->library('session');
    $this->session->unset_userdata('id');
    $this->session->unset_userdata('nombre');
    $this->session->unset_userdata('es_admin');
    redirect('/');
  }
}