<?php

namespace App\Models;

use PDO;
use App\Models\ConectDB;

/**
 * Autor: @ZRJChrist
 *
 * Descripción: Modelo que maneja operaciones relacionadas con provincias.
 *  
 * Fecha de creación: 23/11/2023
 * 
 */
class Provinces
{
    public static function getProvinces()
    {
        $connection = ConectDB::getInstance()->getConnection();
        $query = $connection->prepare('SELECT province_id, name_province FROM provinces');
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        // Itera sobre los resultados y construye el array asociativo con ID de provincia y nombre de provincia.
        foreach ($result as $row) {
            $listProvinces[$row['province_id']] = $row['name_province'];
        }

        // Retorna el array asociativo de provincias.
        return $listProvinces;
    }
}
