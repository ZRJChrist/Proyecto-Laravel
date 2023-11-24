<?php

namespace App\Models;

use PDO;
use PDOException;
use App\Models\ConectDB;

class Task
{
    private $AllProperties = [];
    private const FormProperties = [
        'user_id',
        'firstName',
        'lastName',
        'nif_cif',
        'phoneNumber',
        'email',
        'description',
        'codigoPostal',
        'location',
        'province_id',
        'status_task',
        'date_task',
        'operario',
        'inf_task'
    ];
    public static function create($request, bool $form = false)
    {

        $connection = ConectDB::getInstance()->getConnection();
        $connection->beginTransaction();

        /*
        * Utilizando array_map a cada nombre de las columnas le agregamos el doble punto ( : ),
        * para despues poder pasar los valores con su correspondiente columna.
        */
        $values = self::stringColums(array_map(function ($campo) {
            return ':' . $campo;
        }, self::FormProperties));

        $query = $connection->prepare('INSERT INTO task (' . self::stringColums(self::FormProperties) . ') 
        VALUES(' . $values . ')');
        if ($query->execute([
            ':user_id' => $request['user_id'],
            ':firstName' => $request['firstName'],
            ':lastName' => $request['lastName'],
            ':nif_cif' => $request['nif_cif'],
            ':phoneNumber' => $request['phoneNumber'],
            ':email' => $request['email'],
            ':description' => $request['description'],
            ':codigoPostal' => $request['codigoPostal'],
            ':location' => $request['location'],
            ':province_id' => $request['provinces'],
            ':status_task' => $request['status'],
            ':date_task' => $request['date_task'],
            ':operario' => $request['operario'],
            ':inf_task' => $request['inf_task'],
        ])) {
            $connection->commit();
            return ['result' => true, 'message' => 'Tarea creada'];
        } else {
            $connection->rollBack();
            return ['result' => false, 'message' => 'Error al crear tarea'];
        }
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
    private static function stringColums($dataRequest)
    {
        $campos = implode(', ', $dataRequest);
        return $campos;
    }
}
