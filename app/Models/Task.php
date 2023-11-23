<?php

namespace App\Models;

use PDO;
use PDOException;
use App\Models\ConectDB;
use App\Models\Query;
use Illuminate\Http\Client\Request;

class Task
{
    public function __construct()
    {
    }
    public function create(Request $request)
    {
        $connection = ConectDB::getInstance()->getConnection();
        $connection->beginTransaction();
        $query = $connection->prepare('INSERT INTO task (
            user_id, description,name_person, nif_cif, email,codigoPostal,
            location, province_id, status_task, date_task, operario
        ) VALUES(
            :user_id, :description, :name_person, :nif_cif, :email, :codigoPostal, :location, :province_id, :status_task, :date_task, :operario
        )');
        $request = $request->except('_token');
        $query->execute([
            'user_id' => $request['user_id'],
            'description' => $request[''],
        ]);
    }
    public function getAll()
    {
    }
    public function find($id)
    {
    }
    public function delete($id)
    {
    }
    public function update($request, $id)
    {
    }
}
