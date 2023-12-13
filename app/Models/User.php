<?php

namespace App\Models;

use Illuminate\Support\Facades\Hash;
use PDO;
use PDOException;
use App\Models\ConectDB;
use App\Models\BaseModel;

/**
 * Autor: @ZRJChrist
 *
 * Descripción: Modelo que se utiliza para realizar operaciones relacionadas con los usuarios en la base de datos. Proporciona métodos para crear usuarios, verificar la existencia de un email, obtener credenciales, obtener ID y rol, obtener operarios, obtener nombres, y buscar tokens de recordatorio. La descripción del autor y la fecha de creación también están incluidas en los comentarios.
 *  
 * Fecha de creación: 23/11/2023
 * 
 */
class User extends BaseModel
{
    /**
     * Obtiene el nombre de la tabla asociada al modelo.
     *
     * @return string Retorna el nombre de la tabla.
     */
    protected static function getTableName()
    {
        return 'users';
    }
    /**
     * Obtiene el nombre de la columna ID asociada al modelo.
     *
     * @return string Retorna el nombre de la columna ID.
     */
    protected static function getIdColumn()
    {
        return 'id';
    }

    /**
     * Crea un nuevo usuario en la base de datos.
     *
     * @param array $request Datos del usuario a crear.
     * @return array Retorna un array con el resultado y el mensaje de la operación.
     */
    public static function create($request)
    {
        try {
            // Verifica si el email ya existe en la base de datos.
            if (self::checkIfExistsEmail($request['email'])) {
                return ['result' => false, 'message' => 'El email ya se encuetra registrado'];
            } else {
                // Obtiene la instancia de la conexión a la base de datos y comienza una transacción.
                $connection = ConectDB::getInstance()->getConnection();
                $connection->beginTransaction();

                // Prepara la consulta SQL para insertar un nuevo usuario.
                $sentencia = $connection->prepare('INSERT INTO users (name, last_name,role,email, nif_cif,phoneNumber,password) VALUES (:name, :last_name,:role ,:email, :nif_cif ,:phoneNumber, :password)');

                // Asigna valores a los parámetros de la consulta.
                if ($sentencia->execute([
                    ':name' => $request['name'],
                    ':last_name' => $request['last_name'],
                    ':role' => $request['role'],
                    ':email' => $request['email'],
                    ':nif_cif' => $request['nif_cif'],
                    ':phoneNumber' => $request['phoneNumber'],
                    ':password' => Hash::make($request['password'], ['rounds' => 12])
                ])) {
                    $connection->commit();
                    return ['result' => true, 'message' => 'Usuario creado exitosamente.'];
                } else {
                    $connection->rollBack();
                    return ['result' => false, 'message' => 'Error al crear el usuario.'];
                }
            }
        } catch (PDOException $e) {
            return ['result' => false, 'message' => 'Error de base de datos: ' . $e->getMessage()];
        }
    }

    /**
     * Verifica si un email ya existe en la base de datos.
     *
     * @param string $email Email a verificar.
     * @return bool Retorna true si el email ya existe, de lo contrario, false.
     */
    public static function checkIfExistsEmail($email)
    {
        $sentencia = ConectDB::getInstance()->getConnection()->prepare('SELECT email FROM users WHERE email = :email');
        $sentencia->execute(array(':email' => $email));
        $resultado = $sentencia->fetch(PDO::FETCH_ASSOC);
        return ($resultado !== false);
    }

    /**
     * Obtiene la contraseña cifrada de un usuario.
     *
     * @param array $credentials Credenciales del usuario (en este caso, solo el email).
     * @return string Retorna la contraseña cifrada del usuario.
     */
    public static function credentials($credentials)
    {
        $query = ConectDB::getInstance()->getConnection()->prepare('SELECT password FROM users WHERE email = :email');
        $query->execute(array(':email' => $credentials['email']));
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result['password'];
    }

    /**
     * Obtiene el ID y el rol de un usuario a partir de su email.
     *
     * @param string $email Email del usuario.
     * @return array Retorna un array con el ID y el rol del usuario.
     */
    public static function getIdAndRole($email)
    {
        $query = ConectDB::getInstance()->getConnection()->prepare('SELECT id, role FROM users WHERE email = :email');
        $query->execute([':email' => $email]);
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * Obtiene un array de todos los operarios (usuarios con rol 0) en la base de datos.
     *
     * @return array Retorna un array con los ID y nombres de todos los operarios.
     */
    public static function getAllOperarios()
    {
        $query = ConectDB::getInstance()->getConnection()->prepare('SELECT id, name FROM users WHERE role = 0');
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        foreach ($result as $row) {
            $operarios[$row['id']] = $row['name'];
        }
        return $operarios;
    }

    /**
     * Obtiene el nombre de un usuario a partir de su ID.
     *
     * @param int $id ID del usuario.
     * @return array Retorna un array con el nombre del usuario.
     */
    public static function getName($id)
    {
        $query = ConectDB::getInstance()->getConnection()->prepare('SELECT name FROM users WHERE id = :id');
        $query->execute([':id' => $id]);
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * Busca un token de recordatorio en la base de datos.
     *
     * @param string $token Token de recordatorio.
     * @return array|null Retorna un array con el email asociado al token, o null si no se encuentra.
     */
    public static function searchToken($token)
    {
        $connection = ConectDB::getInstance()->getConnection();
        $query = $connection->prepare('SELECT email FROM users WHERE remember_token = :token');
        $query->execute([':token' => $token]);
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
}
