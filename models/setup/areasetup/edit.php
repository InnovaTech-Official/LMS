<?php
require_once('../../../includes/db_wrapper.php');

function updateArea($id, $cityId, $areaName) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("UPDATE area SET city_id=:city_id, name=:name WHERE id=:id");
        $stmt->bindParam(':city_id', $cityId, PDO::PARAM_INT);
        $stmt->bindParam(':name', $areaName);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return ["success" => true, "message" => "Area updated successfully!"];
    } catch (PDOException $e) {
        return ["success" => false, "message" => "Error updating area: " . $e->getMessage()];
    }
}
?>