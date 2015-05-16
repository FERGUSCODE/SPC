<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Graficos extends CI_Controller {
  public function __construct(){
    parent::__construct();
    $this->load->helper('url');

    $this->load->helper('array');
    //session
    $this->load->library('session');
    if($this->session->userdata('login') !=true){
      redirect('usuarios');
    }	
  }
  
  public function index(){
    $usuario=$this->session->userdata('usuario');
    $query1 = $this->planilla_cocedor->get_all();
    $query2 = $this->planilla_secador->get_all();
    $query3 = $this->planilla_prensa->get_all();
    $query4 = $this->planilla_psconcentrado->get_all();
    $data = array('cocedor'=>$query1,'secador'=>$query2,'prensa'=>$query3,'psconcentrado'=>$query4);
    $this->load->view("grafico/index",$data);
  }

  public function prensa(){
    $usuario=$this->session->userdata('usuario');
    $query1 = $this->planilla_cocedor->get_all();
    $query2 = $this->planilla_secador->get_all();
    $query3 = $this->planilla_prensa->get_all();
    $query4 = $this->planilla_psconcentrado->get_all();
    $data = array('cocedor'=>$query1,'secador'=>$query2,'prensa'=>$query3,'psconcentrado'=>$query4);
    $this->load->view("grafico/prensa",$data);
  }

  public function secador(){
    $usuario=$this->session->userdata('usuario');
    $query1 = $this->planilla_cocedor->get_all();
    $query2 = $this->planilla_secador->get_all();
    $query3 = $this->planilla_prensa->get_all();
    $query4 = $this->planilla_psconcentrado->get_all();
    $data = array('cocedor'=>$query1,'secador'=>$query2,'prensa'=>$query3,'psconcentrado'=>$query4);
    $this->load->view("grafico/secador",$data);
  }

  public function psconcentrado(){
    $usuario=$this->session->userdata('usuario');
    $query1 = $this->planilla_cocedor->get_all();
    $query2 = $this->planilla_secador->get_all();
    $query3 = $this->planilla_prensa->get_all();
    $query4 = $this->planilla_psconcentrado->get_all();
    $data = array('cocedor'=>$query1,'secador'=>$query2,'prensa'=>$query3,'psconcentrado'=>$query4);
    $this->load->view("grafico/psconcentrado",$data);
  }
}