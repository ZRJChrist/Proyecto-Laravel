<?php

namespace App\Models;

use PDO;
use PDOException;
use App\Models\ConectDB;

class Task
{
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

    public static function getAll($column = false, $limit = false, mixed $param = false)
    {
        $connection = ConectDB::getInstance()->getConnection();

        $columns = $column ? self::stringColums($column) : '*';
        $sql = "SELECT $columns FROM task";

        if ($param) {
            $paramKey = key($param);
            $sql .= " WHERE $paramKey = :paramValue";
        }

        if ($limit) {
            $sql .= ' LIMIT :init , :reg';
        }

        $query = $connection->prepare($sql);

        if ($param) {
            $query->bindValue(':paramValue', $param[$paramKey]);
        }

        if ($limit) {
            $query->bindValue(':init', $limit['init'], PDO::PARAM_INT);
            $query->bindValue(':reg', $limit['reg'], PDO::PARAM_INT);
        }

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
        $result = $query->fetch(PDO::FETCH_ASSOC);
        if (empty($result)) {
            return ['result' => false, 'message' => 'No hay datos'];
        }
        return ['result' => true, 'data' => $result];
    }

    public static function update($id, $request)
    {
        $columns = self::stringColums(array_map(function ($campo) {
            return $campo . '= :' . $campo;
        }, array_keys($request)));

        $connection = ConectDB::getInstance()->getConnection();
        $slq = 'UPDATE task SET ' . $columns . ' WHERE task_id= :id';
        $query = $connection->prepare($slq);
        $request['id'] = $id;
        if ($query->execute($request)) {
            return ['result' => true, 'message' => 'Tarea ' . $id . ' actualizada'];
        } else {
            return ['result' => false, 'message' =>  $connection->errorInfo()];
        }
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
    public static function numRegister(mixed $param = false)
    {
        $conn = ConectDB::getInstance()->getConnection();
        $sql = "SELECT COUNT(*) as total FROM task";

        if ($param) {
            $paramKey = key($param);
            $sql .= " WHERE $paramKey = :paramValue";
        }

        $query = $conn->prepare($sql);

        if ($param) {
            $query->bindValue(':paramValue', $param[$paramKey]);
        }

        return $query->execute() ? $query->fetchColumn() : false;
    }

    private static function stringColums($dataRequest)
    {
        $campos = implode(', ', $dataRequest);
        return $campos;
    }
}
