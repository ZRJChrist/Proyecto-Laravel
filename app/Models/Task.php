<?php

namespace App\Models;

use PDO;
use PDOException;
use App\Models\ConectDB;
use App\Models\BaseModel;

class Task extends BaseModel
{
    private const FormProperties = [
        'firstName',
        'lastName',
        'nif_cif',
        'phoneNumber',
        'email',
        'description',
        'codigoPostal',
        'direccion',
        'location',
        'province_id',
        'status_task',
        'date_task',
        'operario',
        'inf_task',
        'archivePdf',
        'archiveImg'
    ];
    protected static function getTableName()
    {
        return 'task';
    }

    protected static function getIdColumn()
    {
        return 'task_id';
    }
    public static function create($request)
    {

        try {

            $connection = ConectDB::getInstance()->getConnection();
            $connection->beginTransaction();

            /*
            * Utilizando array_map a cada nombre de la columna se agrega el doble punto ( : ),
            * para despues poder pasar los valores con su correspondiente columna.
            */
            $values = self::stringColums(array_map(function ($campo) {
                return ':' . $campo;
            }, self::FormProperties));

            $query = $connection->prepare('INSERT INTO task (' . self::stringColums(self::FormProperties) . ') 
        VALUES(' . $values . ')');
            $param = [];
            foreach (self::FormProperties as $key) {
                $param[':' . $key] = $request[$key];
            }
            if ($query->execute($param)) {
                $connection->commit();
                return ['result' => true, 'message' => 'Tarea creada'];
            } else {
                $connection->rollBack();
                return ['result' => false, 'message' => 'Error al crear tarea'];
            }
        } catch (PDOException $e) {
            return ['result' => false, 'message' => 'Error de base de datos: ' . $e->getMessage()];
        }
    }

    public static function lastID()
    {
        $connection = ConectDB::getInstance()->getConnection();
        $query = $connection->prepare('SELECT max(task_id) as task_id FROM task');
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result['task_id'];
    }

    public static function delete($id)
    {
        $connection = ConectDB::getInstance()->getConnection();
        $query = $connection->prepare('DELETE FROM task WHERE task_id = :id');
        if ($query->execute([':id' => $id])) {
            return ['result' => true, 'message' => 'Tarea ' . $id . ' Eliminada'];
        } else {
            return ['result' => false, 'message' =>  $connection->errorInfo()];
        }
    }
    private static function stringColums($dataRequest)
    {
        $campos = implode(', ', $dataRequest);
        return $campos;
    }
}
