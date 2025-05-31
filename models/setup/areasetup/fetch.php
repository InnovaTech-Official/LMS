<?php
require_once('../../../includes/db_wrapper.php');

function getAllAreas() {
    global $pdo;
    try {
        $sql = "SELECT a.id, a.name as area_name, c.id as city_id, c.name as city_name 
                FROM area a 
                JOIN city c ON a.city_id = c.id 
                ORDER BY c.name, a.name";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return ["error" => $e->getMessage()];
    }
}

function getAreaById($id) {
    global $pdo;
    try {
        $sql = "SELECT a.id, a.name as area_name, c.id as city_id, c.name as city_name 
                FROM area a 
                JOIN city c ON a.city_id = c.id 
                WHERE a.id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return ["error" => $e->getMessage()];
    }
}
?>