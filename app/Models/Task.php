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
        'direccion',
        'location',
        'province_id',
        'status_task',
        'date_task',
        'operario',
        'inf_task'
    ];
    public static function create($request, bool $form = false)
    {
        try {

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

    public static function getAll($column = false, $limit = false)
    {
        $connection = ConectDB::getInstance()->getConnection();
        $sql = '';
        if (!$column) {
            $sql = 'SELECT * FROM task';
        } else {
            $sql = 'SELECT ' . self::stringColums($column) . ' FROM task';
        }
        if ($limit) {
            $sql .= ' LIMIT ' . $limit['init'] . ' , ' . $limit['reg'];
        }
        $query = $connection->prepare($sql);
        if (!$query->execute()) {
            return ['result' => false, 'message' => $connection->errorInfo()];
        }
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return ['result' => true, 'data' => $result];
    }

    public static function find($id, $column = false)
    {
        $connection = ConectDB::getInstance()->getConnection();
        $sql = '';
        if (!$column) {
            $sql = 'SELECT * FROM task WHERE task_id = :id';
        } else {
            $sql = 'SELECT ' . self::stringColums($column) . ' FROM task WHERE task_id = :id';
        }
        $query = $connection->prepare($sql);
        if (!$query->execute([':id' => $id])) {
            return ['result' => false, 'message' => $connection->errorInfo()];
        }
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        if (empty($result)) {
            return ['result' => false, 'message' => 'No hay datos'];
        }
        return ['result' => true, 'data' => $result];
    }

    public function delete($id)
    {
    }

    public function update($request, $id)
    {
    }

    public static function numRegister()
    {
        $conn = ConectDB::getInstance()->getConnection();
        $sql = "SELECT COUNT(*) as total FROM task";
        return $conn->query($sql)->fetchColumn();
    }
    private static function stringColums($dataRequest)
    {
        $campos = implode(', ', $dataRequest);
        return $campos;
    }
}
