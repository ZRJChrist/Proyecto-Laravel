<?php

namespace App\Models;

use PDO;
use PDOException;
use App\Models\ConectDB;
use App\Models\BaseModel;

/**
 * Autor: @ZRJChrist
 *
 * Descripción: Modelo que se utiliza para realizar operaciones relacionadas con 
 * la lógica relacionada con la manipulación de datos de tareas en la base de datos, 
 * proporcionando métodos para la creación y eliminación de tareas, así como la obtención de información relevante sobre las tareas almacenadas.
 *  
 * Fecha de creación: 23/11/2023
 */
class Task extends BaseModel
{
    // Definición de las propiedades de formulario asociadas a las columnas de la tabla de tareas
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

    /**
     * Obtiene el nombre de la tabla asociada al modelo.
     *
     * @return string Retorna el nombre de la tabla.
     */
    protected static function getTableName()
    {
        return 'task';
    }

    /**
     * Obtiene el nombre de la columna ID asociada al modelo.
     *
     * @return string Retorna el nombre de la columna ID.
     */
    protected static function getIdColumn()
    {
        return 'task_id';
    }

    /**
     * Crea una nueva tarea en la base de datos.
     *
     * @param array $request Datos de la tarea a crear.
     * @return bool Retorna true si la tarea se creó con éxito, de lo contrario, false.
     */
    public static function create($request)
    {

        try {
            // Obtiene la instancia de la conexión a la base de datos y comienza una transacción.
            $connection = ConectDB::getInstance()->getConnection();
            $connection->beginTransaction();

            // Utiliza array_map para agregar el doble punto (:) a cada nombre de columna.
            $values = self::stringColumns(array_map(function ($campo) {
                return ':' . $campo;
            }, self::FormProperties));

            // Prepara la consulta SQL para insertar una nueva tarea.
            $query = $connection->prepare('INSERT INTO task (' . self::stringColumns(self::FormProperties) . ') 
        VALUES(' . $values . ')');

            // Asigna valores a los parámetros de la consulta.

            $param = [];
            foreach (self::FormProperties as $key) {
                $param[':' . $key] = $request[$key];
            }

            // Ejecuta la consulta y realiza el commit si es exitosa, o rollback en caso de error.
            if ($query->execute($param)) {
                $connection->commit();
                // return ['result' => true, 'message' => 'Tarea creada'];
                return true;
            } else {
                $connection->rollBack();
                // return ['result' => false, 'message' => 'Error al crear tarea'];
                return false;
            }
        } catch (PDOException $e) {
            return false;
            // return ['result' => false, 'message' => 'Error de base de datos: ' . $e->getMessage()];
        }
    }

    /**
     * Obtiene el ID de la última tarea creada.
     *
     * @return int Retorna el ID de la última tarea.
     */
    public static function lastID()
    {
        $connection = ConectDB::getInstance()->getConnection();
        $query = $connection->prepare('SELECT max(task_id) as task_id FROM task');
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        // Retorna el ID de la última tarea.
        return $result['task_id'];
    }
    /**
     * Elimina una tarea de la base de datos.
     *
     * @param int $id ID de la tarea a eliminar.
     * @return array Retorna un array con el resultado y el mensaje de la operación.
     */
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
}
