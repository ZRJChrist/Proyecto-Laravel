<?php

namespace App\Models;

use Illuminate\Support\Facades\Hash;
use PDO;
use PDOException;
use App\Models\ConectDB;
use App\Models\BaseModel;

class User extends BaseModel
{
    protected static function getTableName()
    {
        return 'users';
    }

    protected static function getIdColumn()
    {
        return 'id';
    }
    public static function create($request)
    {
        try {
            if (self::checkIfExistsEmail($request['email'])) {
                return ['result' => false, 'message' => 'El email ya se encuetra registrado'];
            } else {
                $connection = ConectDB::getInstance()->getConnection();
                $connection->beginTransaction();

                $sentencia = $connection->prepare('INSERT INTO users (name, last_name,role,email, nif_cif,phoneNumber,password) VALUES (:name, :last_name,:role ,:email, :nif_cif ,:phoneNumber, :password)');

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
    public static function checkIfExistsEmail($email)
    {
        $sentencia = ConectDB::getInstance()->getConnection()->prepare('SELECT email FROM users WHERE email = :email');
        $sentencia->execute(array(':email' => $email));
        $resultado = $sentencia->fetch(PDO::FETCH_ASSOC);
        return ($resultado !== false);
    }
    public static function credentials($credentials)
    {
        $query = ConectDB::getInstance()->getConnection()->prepare('SELECT password FROM users WHERE email = :email');
        $query->execute(array(':email' => $credentials['email']));
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result['password'];
    }
    public static function getIdAndRole($email)
    {
        $query = ConectDB::getInstance()->getConnection()->prepare('SELECT id, role FROM users WHERE email = :email');
        $query->execute([':email' => $email]);
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
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
    public static function getName($id)
    {
        $query = ConectDB::getInstance()->getConnection()->prepare('SELECT name FROM users WHERE id = :id');
        $query->execute([':id' => $id]);
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
    public static function searchToken($token)
    {
        $connection = ConectDB::getInstance()->getConnection();
        $query = $connection->prepare('SELECT email FROM users WHERE remember_token = :token');
        $query->execute([':token' => $token]);
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result;
    }
}
