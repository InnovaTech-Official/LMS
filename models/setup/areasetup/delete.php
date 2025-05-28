<?php
require_once('../../../includes/db_wrapper.php');

function deleteArea($id) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("DELETE FROM area WHERE id=:id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return ["success" => true, "message" => "Area deleted successfully!"];
    } catch (PDOException $e) {
        return ["success" => false, "message" => "Error deleting area: " . $e->getMessage()];
    }
}
?>