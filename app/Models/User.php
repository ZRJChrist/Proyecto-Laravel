<?php

namespace App\Models;

use Illuminate\Support\Facades\Hash;
use PDO;
use PDOException;
use App\Models\ConectDB;

class User
{
    public static function createUser(array $user)
    {
        try {
            if (self::checkIfExistsEmail($user['email'])) {
                return ['result' => false, 'message' => 'El email ya se encuetra registrado'];
            } else {
                $connection = ConectDB::getInstance()->getConnection();
                $connection->beginTransaction();

                $sentencia = $connection->prepare('INSERT INTO users (name, email, password) VALUES (:name, :email, :password)');

                if ($sentencia->execute([
                    ':name' => $user['name'],
                    ':email' => $user['email'],
                    ':password' => Hash::make($user['password'], ['rounds' => 12])
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
    public static function getDataUser($user_id)
    {
        $connection = ConectDB::getInstance()->getConnection();
        $query = $connection->prepare('SELECT name,last_name, email, phoneNumber, nif_cif ,role FROM users WHERE id = :id');
        $query->execute([':id' => $user_id]);
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        return $result[0];
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
    public static function getUser($email)
    {
        $query = ConectDB::getInstance()->getConnection()->prepare('SELECT id FROM users WHERE email = :email');
        $query->execute([':email' => $email]);
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result['id'];
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
}
