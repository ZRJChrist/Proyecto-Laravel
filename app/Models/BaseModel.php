<?php

namespace App\Models;

use PDO;
use App\Models\ConectDB;

/**
 * Autor: @ZRJChrist
 *
 * Descripción: Clase abstracta que sirve como base para los modelos de la base de datos.
 * Proporciona métodos comunes para realizar operaciones Leer, Actualizar
 * 
 * TODO Ampliar/Agregar la funciones para poder hacer operaciones CRUD
 * 
 * Fecha de creación: 9/12/2023
 */
abstract class BaseModel
{
    /**
     * Método abstracto para obtener el nombre de la tabla en la base de datos.
     * @return string Retorna el nombre de la tabla.
     */
    abstract protected static function getTableName();

    /**
     * Método abstracto para obtener el nombre de la columna de ID en la tabla.
     * @return string Retorna el nombre de la columna de ID.
     */
    abstract protected static function getIdColumn();
    /**
     * Actualiza los datos de un registro en la base de datos.
     *
     * @param int $id ID del registro a actualizar.
     * @param array $request Datos a actualizar.
     * @return array Retorna un array con el resultado de la operación y un mensaje.
     */
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

    /**
     * Obtiene todos los registros de la tabla con opcionalmente filtros y límite de resultados.
     *
     * @param array|false $column Columnas a seleccionar, o falso para seleccionar todas (*).
     * @param array|false $limit Límite de resultados y offset.
     * @param array|false $conditions Condiciones de filtrado opcionales.
     * @return array Retorna un array con el resultado de la operación y los datos obtenidos.
     */
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
        //dd($sql, $column, $conditions, $limit);
        if (!$query->execute()) {
            return ['result' => false, 'message' => $connection->errorInfo()];
        }

        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return ['result' => true, 'data' => $result];
    }

    /**
     * Obtiene un registro específico por su ID.
     *
     * @param int $id ID del registro a obtener.
     * @param array|false $column Columnas a seleccionar, o falso para seleccionar todas (*).
     * @return array Retorna un array con el resultado de la operación y los datos obtenidos.
     */
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

    /**
     * Obtiene el número total de registros en la tabla, opcionalmente filtrados.
     *
     * @param array|false $param Condiciones de filtrado opcionales.
     * @return int|false Retorna el número total de registros o falso en caso de error.
     */
    public static function numRegister(mixed $param = false)
    {
        $conn = ConectDB::getInstance()->getConnection();
        $sql = "SELECT COUNT(*) as total FROM " . static::getTableName();

        if (!empty($param)) {
            $sql .= " WHERE ";
            $paramSql = [];

            foreach ($param as $key => $value) {
                $paramSql[] = "$key = :$key";
            }

            $sql .= implode(' AND ', $paramSql);
        }
        $query = $conn->prepare($sql);

        if (!empty($param)) {
            foreach ($param as $key => $value) {
                $query->bindValue(":$key", $value);
            }
        }
        return $query->execute() ? $query->fetchColumn() : false;
    }
    /**
     * Convierte un array de nombres de columnas en una cadena separada por comas.
     *
     * @param array $columns Nombres de las columnas.
     * @return string Retorna una cadena de columnas separadas por comas.
     */
    protected static function stringColumns($columns)
    {
        return implode(', ', $columns);
    }
}
