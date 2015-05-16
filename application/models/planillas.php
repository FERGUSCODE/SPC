<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Planillas extends CI_Model {
  public function __construct() {
    parent::__construct();
    $this->load->database();
  }

  public function get_usuarios_planilla($planilla_id) {
    $this->db->select('nombre');
    $this->db->join('usuario', 'usuario.id = usuario_id');
    $this->db->where('planilla_id', $planilla_id);
    $query = $this->db->get('planilla_acceso');
    return $query->result();
  }

  public function get_maquina_by_id($maquina_id) {
    $this->db->select('sector_id, nombre, min, max, unidad');
    $this->db->where('id', $maquina_id);
    $query = $this->db->get('base_planta_sector_maquina', 1);
    return $query->row(0);
  }

  public function get_maquinas_by_sector_id($sector_id) {
    $this->db->select('id, nombre, min, max, unidad');
    $this->db->where('sector_id', $sector_id);
    $query = $this->db->get('base_planta_sector_maquina');
    return $query->result();
  }

  public function get_sector_data_by_url($sector_url) {
    $this->db->select('id, planta_id, nombre, medida');
    $this->db->where('url', $sector_url);
    $query = $this->db->get('base_planta_sector', 1);
    return $query->row(0);
  }

  public function get_by_sector_url($sector_url, $limit = null) {
    $this->db->select('planilla.id, base.nombre, base_planta.nombre, base_planta_sector.nombre, fecha');
    $this->db->join('base_planta_sector', 'base_planta_sector.id = planilla.sector_id');
    $this->db->join('base_planta', 'base_planta.id = base_planta_sector.planta_id');
    $this->db->join('base', 'base.id = base_planta.base_id');
    $this->db->where('base_planta_sector.url', $sector_url);
    $this->db->order_by('fecha', 'desc');
    $query = $this->db->get('planilla', $limit);
    return $query->result();
  }

  public function get_by_id($id) {
    $this->db->select('planilla.id, base.nombre, base_planta.nombre, base_planta_sector.nombre, fecha');
    $this->db->join('base_planta_sector', 'base_planta_sector.id = planilla.sector_id');
    $this->db->join('base_planta', 'base_planta.id = base_planta_sector.planta_id');
    $this->db->join('base', 'base.id = base_planta.base_id');
    $this->db->where('planilla.id', $id);
    $query = $this->db->get('planilla', 1);
    return $query->row(0);
  }

  public function get_all_sector() {
    return $query = $this->db->get('base_planta_sector')->result();
  }

  public function get_sector_acceso($usuario_id) {
    $this->db->select('planilla_id, base_planta_sector.nombre, base_planta_sector.url, fecha');
    $this->db->join('planilla', 'planilla.id = planilla_acceso.planilla_id');
    $this->db->join('base_planta_sector', 'base_planta_sector.id = planilla.sector_id');
    $this->db->where('usuario_id', $usuario_id);
    $this->db->order_by('fecha', 'desc');
    $query = $this->db->get('planilla_acceso');
    return $query->result();
  }

  public function insert($sector_id, $fecha, $usuarios) {
    $this->db->insert('planilla', array(
      'sector_id' => $sector_id,
      'fecha' => $fecha
    ));
    $planilla_id = $this->db->insert_id();

    $this->db->trans_start();
    for ($i = 0, $cantidad = sizeof($usuarios); $i < $cantidad; ++$i) {
      $query = $this->db->insert('planilla_acceso', array(
        'planilla_id' => $planilla_id,
        'usuario_id' => $usuarios[$i]
      ));
    }
    $this->db->trans_complete();
  }

  public function update($id, $sector_id, $fecha, $usuarios) {
    $this->db->where('id', $id);
    $query = $this->db->update('planilla', array(
      'sector_id' => $sector_id,
      'fecha' => $fecha
    ));

    $this->db->trans_start();
    $this->db->delete('planilla_acceso', array('planilla_id' => $id));
    for ($i = 0, $cantidad = sizeof($usuarios); $i < $cantidad; ++$i) {
      $query = $this->db->insert('planilla_acceso', array(
        'planilla_id' => $id,
        'usuario_id' => $usuarios[$i]
      ));
    }
    $this->db->trans_complete(); 
  }
}