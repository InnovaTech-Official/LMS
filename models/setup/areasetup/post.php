<?php
require_once('../../../includes/db_wrapper.php');

function createArea($cityId, $areaName) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO area (city_id, name) VALUES (:city_id, :name)");
        $stmt->bindParam(':city_id', $cityId, PDO::PARAM_INT);
        $stmt->bindParam(':name', $areaName);
        $stmt->execute();
        return ["success" => true, "message" => "Area added successfully!"];
    } catch (PDOException $e) {
        return ["success" => false, "message" => "Error adding area: " . $e->getMessage()];
    }
}
?>