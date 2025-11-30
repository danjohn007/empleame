<?php
/**
 * Modelo Empresa
 * Alianza Inclusiva Tech
 */

class Empresa {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findById($id) {
        $stmt = $this->db->prepare("SELECT * FROM empresas WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function findByUsuarioId($usuarioId) {
        $stmt = $this->db->prepare("SELECT * FROM empresas WHERE usuario_id = ?");
        $stmt->execute([$usuarioId]);
        return $stmt->fetch();
    }

    public function findByRFC($rfc) {
        $stmt = $this->db->prepare("SELECT * FROM empresas WHERE rfc = ?");
        $stmt->execute([strtoupper($rfc)]);
        return $stmt->fetch();
    }

    public function all() {
        $stmt = $this->db->query("SELECT * FROM empresas ORDER BY razon_social");
        return $stmt->fetchAll();
    }

    public function verificadas() {
        $stmt = $this->db->query("SELECT * FROM empresas WHERE verificada = 1 ORDER BY razon_social");
        return $stmt->fetchAll();
    }

    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO empresas (usuario_id, razon_social, rfc, nombre_comercial, direccion_fiscal,
            colonia, municipio, codigo_postal, sector_industrial, tamano_empresa, telefono)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['usuario_id'],
            $data['razon_social'],
            strtoupper($data['rfc']),
            $data['nombre_comercial'] ?? null,
            $data['direccion_fiscal'],
            $data['colonia'] ?? null,
            $data['municipio'],
            $data['codigo_postal'],
            $data['sector_industrial'],
            $data['tamano_empresa'] ?? 'pequena',
            $data['telefono'] ?? null
        ]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $fields = [];
        $values = [];

        foreach ($data as $key => $value) {
            $fields[] = "$key = ?";
            $values[] = $value;
        }
        $values[] = $id;

        $stmt = $this->db->prepare("
            UPDATE empresas SET " . implode(', ', $fields) . ", fecha_actualizacion = NOW() WHERE id = ?
        ");
        return $stmt->execute($values);
    }

    public function verificar($id, $verificada = true) {
        $stmt = $this->db->prepare("UPDATE empresas SET verificada = ? WHERE id = ?");
        return $stmt->execute([$verificada ? 1 : 0, $id]);
    }
}
