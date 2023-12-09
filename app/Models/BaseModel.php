<?php

namespace App\Models;

use PDO;
use App\Models\ConectDB;

abstract class BaseModel
{
    abstract protected static function getTableName();
    abstract protected static function getIdColumn();
    public static function update($id, $request)
    {
        $columns = self::stringColumns(array_map(function ($campo) {
            return $campo . '= :' . $campo;
        }, array_keys($request)));

        $connection = ConectDB::getInstance()->getConnection();
        $slq = 'UPDATE ' . static::getTableName() . ' SET ' . $columns . ' WHERE ' . static::getIdColumn() . ' = :id';
        $query = $connection->prepare($slq);
        $request['id'] = $id;
        if ($query->execute($request)) {
            return ['result' => true, 'message' => 'Tarea ' . $id . ' actualizada'];
        } else {
            return ['result' => false, 'message' =>  $connection->errorInfo()];
        }
    }
    public static function getAll($column = false, $limit = false, mixed $conditions = false)
    {
        $connection = ConectDB::getInstance()->getConnection();

        $columns = $column ? self::stringColumns($column) : '*';
        $sql = "SELECT $columns FROM " . static::getTableName();

        if (!empty($conditions)) {
            $sql .= " WHERE ";
            $conditionsSql = [];

            foreach ($conditions as $key => $value) {
                $conditionsSql[] = "$key = :$key";
            }

            $sql .= implode(' AND ', $conditionsSql);
        }
        if (static::getTableName() == 'task')
            $sql .= ' ORDER BY date_task DESC';

        if ($limit) {
            $sql .= ' LIMIT :init , :reg';
        }

        //dd($sql);
        $query = $connection->prepare($sql);
        if (!empty($conditions)) {
            foreach ($conditions as $key => $value) {
                $query->bindValue(":$key", $value);
            }
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
            $sql = 'SELECT * FROM ' . static::getTableName() . ' WHERE ' . static::getIdColumn() . ' = :id';
        } else {
            $sql = 'SELECT ' . self::stringColumns($column) . ' FROM ' . static::getTableName() . ' WHERE ' . static::getIdColumn() . ' = :id';
        }
        $query = $connection->prepare($sql);
        if (!$query->execute([':id' => $id])) {
            return ['result' => false, 'message' => $connection->errorInfo()];
        }
        $result = $query->fetch(PDO::FETCH_ASSOC);
        if (!$result) {
            return ['result' => false, 'message' => 'No hay datos'];
        }
        return ['result' => true, 'data' => $result];
    }
    public static function numRegister(mixed $param = false)
    {
        $conn = ConectDB::getInstance()->getConnection();
        $sql = "SELECT COUNT(*) as total FROM " . static::getTableName();

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

    protected static function stringColumns($columns)
    {
        return implode(', ', $columns);
    }
}
