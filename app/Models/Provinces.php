<?php

namespace App\Models;

use PDO;
use App\Models\ConectDB;

class Provinces
{
    public static function getProvinces()
    {
        $connection = ConectDB::getInstance()->getConnection();
        $query = $connection->prepare('SELECT province_id, name_province FROM provinces');
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row) {
            $listProvinces[$row['province_id']] = $row['name_province'];
        }
        return $listProvinces;
    }
}
