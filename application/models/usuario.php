<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class Usuario extends CI_Model {
  public function __construct() {
    parent::__construct();
    $this->load->database();
  }

  // Verificar
  public function validar_login($usuario, $contrasena) {
    $this->db->select('id, contrasena');
    $this->db->where('usuario', $usuario);
    $result = $this->db->get('usuario', 1)->row(0);

    return ($result && $result->contrasena == $contrasena) ? $result->id : 0;
  }

  // Obtener datos
  public function get($id) {
    $this->db->select('nombre, isAdmin');
    $this->db->where('id', $id);
    $query = $this->db->get('usuario', 1);
    return $query->row(0);
  }

  // Obtener id y nombre de todos los usuarios
  public function get_all() {
    $this->db->select('id, usuario, nombre');
    $query = $this->db->get('usuario');
    return $query->result();
  }

  public function registro_usuario($usuario, $contrasena, $nombre, $isAdmin) {
    $query = $this->db->insert('usuario', array(
      'usuario' => $usuario,
      'contrasena' => $contrasena,
      'nombre' => $nombre,
      'isAdmin' => $isAdmin
    ));
  }
}