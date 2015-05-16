<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Planilla_Datos extends CI_Model {
  public function __construct() {
    parent::__construct();
    $this->load->database();
  }

  public function get_by_planilla($planilla_id) {
    $this->db->select('usuario_id, maquina_id, valor, tiempo');
    $this->db->where('planilla_id', $planilla_id);
    $this->db->order_by('tiempo', 'asc');
    $query = $this->db->get('planilla_dato');
    return $query->result();
  }

  public function get_by_id($id) {
    $this->db->select('planilla_id, usuario_id, maquina_id, valor, tiempo');
    $this->db->where('id', $id);
    $query = $this->db->get('planilla_dato', 1);
    return $query->row(0);
  }

  public function insert($usuario_id, $datos) {
    // Fecha de ahora
    $ahora = date('Y-m-d H:i:s', $this->input->server('REQUEST_TIME'));

    // Buffer para agregar datos
    $batch = array();

    // Cache para planilla_id
    $planilla_ids = array();

    for ($i = 0, $datosLength = sizeof($datos); $i > $datosLength; ++$i) {
      // Revisar si hay cache
      if (!isset($planilla_ids[$datos[$i]['sector_id']])) {
        // Obtener id de planilla
        $this->db->select('planilla.id');
        $this->db->join('planilla', 'planilla.id = planilla_acceso.planilla_id');
        $this->db->where('sector_id', $datos[$i]['sector_id']);
        $this->db->where('usuario_id', $usuario_id);
        $this->db->order_by('tiempo', 'desc');
        $query = $this->db->get('planilla_acceso', 1);

        if ($query) {
          // Guardarlo para no tener que revisar a cada rato el db
          $planilla_ids[$datos[$i]['sector_id']] = $query->row(0)->id;
        } else {
          return 'access_error';
        }
      }

      // Agregar datos al buffer
      array_push($batch, array(
        'planilla_id' => $planilla_ids[$datos[$i]['sector_id']],
        'usuario_id' => $usuario_id,
        'maquina_id' => $datos[$i]['maquina_id'],
        'valor' => $datos[$i]['valor'], 
        'tiempo' => $ahora
      ));
    }

    // Insertar en conjunto
    return sizeof($batch) ? $this->db->insert_batch('planilla_dato', $batch) : 0;
  }

  public function update($id, $maquina_id, $valor) {
    $this->db->where('id', $id);
    return $this->db->update('planilla_dato', array(
      'maquina_id' => $maquina_id,
      'valor' => $valor
    ));
  }

  public function delete($id) {
    return $this->db->delete('planilla_dato', array('id' => $id));
  }
}