<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Planillas extends CI_Model {
  public function __construct() {
    parent::__construct();
    $this->load->database();
  }

  public function get_usuarios_planilla($planilla_id) {
    $this->db->select('id, nombre');
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

  public function get_sector_data_by_url($sector_url, $planta_id) {
    $this->db->select('id, nombre, medida');
    $this->db->where('planta_id', $planta_id);
    $this->db->where('url', $sector_url);
    $query = $this->db->get('base_planta_sector', 1);
    return $query->row(0);
  }

  public function get_by_sector_url($sector_url, $planta_id, $limit = null) {
    $this->db->select('planilla.id, HEX(UUID) AS UUID, base.nombre, base_planta.nombre, base_planta_sector.nombre, tiempo_inicio, tiempo_final');
    $this->db->join('base_planta_sector', 'base_planta_sector.id = planilla.sector_id');
    $this->db->join('base_planta', 'base_planta.id = base_planta_sector.planta_id');
    $this->db->join('base', 'base.id = base_planta.base_id');
    $this->db->where('base_planta_sector.url', $sector_url);
    $this->db->where('base_planta.id', $planta_id);
    $this->db->order_by('tiempo_inicio', 'desc');
    $query = $this->db->get('planilla', $limit);
    return $query->result();
  }

  public function get_by_UUID($UUID) {
    $this->db->select('planilla.id, HEX(UUID) AS UUID, base.nombre, base_planta.nombre, base_planta_sector.nombre, tiempo_inicio, tiempo_final');
    $this->db->join('base_planta_sector', 'base_planta_sector.id = planilla.sector_id');
    $this->db->join('base_planta', 'base_planta.id = base_planta_sector.planta_id');
    $this->db->join('base', 'base.id = base_planta.base_id');
    $this->db->where('UUID = UNHEX(\'' . str_replace('-', '', $UUID) . '\')');
    $query = $this->db->get('planilla', 1);
    return $query->row(0);
  }

  public function get_all_sector($planta_id) {
    $this->db->where('planta_id', $planta_id);
    return $this->db->get('base_planta_sector')->result();
  }

  public function get_sector_acceso($usuario_id, $planilla_id = 0) {
    $this->db->select('planilla_id, base_planta_sector.nombre, base_planta_sector.url, tiempo_inicio, tiempo_final');
    $this->db->join('planilla', 'planilla.id = planilla_acceso.planilla_id');
    $this->db->join('base_planta_sector', 'base_planta_sector.id = planilla.sector_id');
    $this->db->where('usuario_id', $usuario_id);

    if ($planilla_id) {
      $this->db->where('planilla_id', $planilla_id);
    }

    $this->db->order_by('tiempo_inicio', 'desc');
    $query = $this->db->get('planilla_acceso');
    return $query->result();
  }

  public function insert($sector_id, $tiempo_inicio, $tiempo_final, $usuarios) {
    // Generar UUID versión 5
    $nombre_UUID = '/planilla/' . $sector_id . '/' . $tiempo_inicio;
    $UUIDnamespace = '6ba7b811-9dad-11d1-80b4-00c04fd430c8';
    $nsHex = str_replace('-', '', $UUIDnamespace);
    $nsStr = array();

    // Convertir el namespace del UUID a bits
    for ($i = 0; $i < 32; $i += 2) {
      $nsStr[] = chr(hexdec($nsHex[$i] . $nsHex[$i + 1]));
    }
    $nsStr = implode('', $nsStr);

    // Calcular el valor de hash
    $hash = sha1($nsStr . $nombre_UUID);

    // Regresar el UUID en 16bit
    $planillaUUID = sprintf('%08s%04s%04x%04x%12s',
      // 32 bits para "time_low"
      substr($hash, 0, 8),

      // 16 bits para "time_mid"
      substr($hash, 8, 4),

      // 16 bits para "time_hi_and_version",
      // Los 4 bits más significativas que indica versión 5
      (hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x5000,

      // 16 bits, 8 bits para "clk_seq_hi_res",
      // 8 bits para "clk_seq_low",
      // Dos bits más significantes que mantiene para el variante DCE1.1
      (hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000,

      // 48 bits para "node"
      substr($hash, 20, 12)
    );

    if (0 == sizeof($this->db->select('1', FALSE)->where('UUID = UNHEX(\'' . $planillaUUID . '\')')->get('planilla', 1)->row(0))) {
      $this->db->set('UUID', 'UNHEX(\'' . $planillaUUID . '\')', FALSE);
      $this->db->insert('planilla', array(
        'sector_id' => $sector_id,
        'tiempo_inicio' => $tiempo_inicio, 
        'tiempo_final' => $tiempo_final
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

      return 1;
    } else {
      return 0;
    }
  }

  public function update($id, $sector_id, $tiempo_inicio, $tiempo_final, $usuarios) {
    $this->db->where('id', $id);
    $query = $this->db->update('planilla', array(
      'sector_id' => $sector_id,
      'tiempo_inicio' => $tiempo_inicio, 
      'tiempo_final' => $tiempo_final
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