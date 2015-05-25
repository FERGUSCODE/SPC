<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Graficos extends CI_Controller {
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
  
  public function index($sector_url = '') {
    $planta_id = $this->session->userdata('planta_id');

    $all_sector_data = $this->planillas->get_all_sector($planta_id);

    if (empty($sector_url)) {
      $sector_url = $all_sector_data[0]->url;
    }

    $siguiente_url = '';
    for ($i = 0, $sectorLength = sizeof($all_sector_data); $i < $sectorLength; ++$i) {
      if ($sector_url == $all_sector_data[$i]->url) {
        if ($i == $sectorLength - 1) {
          $i = -1;
        }

        $siguiente_url = $all_sector_data[$i + 1]->url;
        break;
      }
    }

    $sector_data = $this->planillas->get_sector_data_by_url($sector_url, $planta_id);

    $planilla_data = $this->planillas->get_by_sector_url($sector_url, $planta_id, 1);
    $planilla_data = $planilla_data[0];
    $planilla_datos = $this->planilla_datos->get_by_planilla($planilla_data->id);

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
    $this->load->view('operador/grafico', array(
      'titulo' => $sector_data->medida, 
      'fecha' => $planilla_data->fecha,
      'datos' => $datos, 
      'siguiente_url' => 'graficos/' . $siguiente_url
    ));
  }
}