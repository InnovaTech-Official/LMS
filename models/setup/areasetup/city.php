<?php
require_once('../../../includes/db_wrapper.php');

function getAllCities() {
    global $pdo;
    try {
        $sql = "SELECT id, name FROM city ORDER BY name";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return ["error" => $e->getMessage()];
    }
}

function addCity($cityName) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO city (name) VALUES (:name)");
        $stmt->bindParam(':name', $cityName);
        $stmt->execute();
        $newId = $pdo->lastInsertId();
        return ["success" => true, "message" => "City added successfully!", "id" => $newId];
    } catch (PDOException $e) {
        return ["success" => false, "message" => "Error adding city: " . $e->getMessage()];
    }
}

function removeCity($cityId) {
    global $pdo;
    try {
        // First delete all areas associated with this city
        $stmt = $pdo->prepare("DELETE FROM area WHERE city_id = :city_id");
        $stmt->bindParam(':city_id', $cityId, PDO::PARAM_INT);
        $stmt->execute();
        
        // Then delete the city
        $stmt = $pdo->prepare("DELETE FROM city WHERE id = :id");
        $stmt->bindParam(':id', $cityId, PDO::PARAM_INT);
        $stmt->execute();
        
        $count = $stmt->rowCount();
        if ($count > 0) {
            return ["success" => true, "message" => "City and all associated areas deleted successfully!"];
        } else {
            return ["success" => false, "message" => "City not found."];
        }
    } catch (PDOException $e) {
        return ["success" => false, "message" => "Error deleting city: " . $e->getMessage()];
    }
}
?>