<?php

namespace App\Models;

/**
 * Autor: @ZRJChrist
 *
 * Descripción: Se utiliza para realizar validaciones comunes en diferentes campos de formularios. Proporciona métodos para validar nombres, correos 
 * electrónicos, roles, contraseñas, confirmación de contraseñas, provincias, estados, fechas, códigos postales, números de teléfono, operarios, NIF/CIF,
 *  archivos y campos de texto. También incluye métodos auxiliares para sanitizar la entrada y verificar si un valor no está vacío.
 *  
 * Fecha de creación: 23/11/2023
 * 
 */
class Validator
{
    private $errorHandler;

    /**
     * Constructor de la clase. Inicializa el manejador de errores.
     */
    public function __construct()
    {
        $this->errorHandler = new ErrorHandler;
    }

    /**
     * Método estático para sanear la entrada eliminando espacios y caracteres especiales.
     *
     * @param string $data Datos a sanear.
     * @return string Datos saneados.
     */
    public static function sanitizeInput($data)
    {
        $data = trim($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    /**
     * Obtiene la lista de errores del manejador de errores asociado.
     *
     * @return array Lista de errores.
     */
    public function getErrors()
    {
        return $this->errorHandler->getErrorsList();
    }
    /**
     * Verifica si hay errores en el manejador de errores asociado.
     *
     * @return bool Retorna true si hay errores, de lo contrario, false.
     */
    public function hasErrors()
    {
        return $this->errorHandler->hasErrors();
    }

    /**
     * Obtiene el manejador de errores asociado a esta instancia.
     *
     * @return ErrorHandler Instancia del manejador de errores.
     */
    public function getErrorHandler()
    {
        return $this->errorHandler;
    }
    /**
     * Valida un campo de nombre.
     *
     * @param string $value Valor del campo de nombre.
     * @param string $field Nombre del campo.
     * @return bool Retorna true si la validación es exitosa, de lo contrario, false.
     */
    public function validateName($value, $field = 'name')
    {
        $value = self::sanitizeInput($value);
        if (empty($value) || $value == '') {
            $this->errorHandler->addError($field, 'Campo requerido');
            return false;
        }
        if (!preg_match("/^([\p{L}' ]+)$/u", $value)) {
            $this->errorHandler->addError($field, 'No debe contener numeros o digitos');
            return false;
        }
        return true;
    }

    /**
     * Valida un campo de email.
     *
     * @param string $value Valor del campo de email.
     * @param string $field Nombre del campo.
     * @return bool Retorna true si la validación es exitosa, de lo contrario, false.
     */
    public function validateEmail($value, $field = 'email')
    {
        $value = self::sanitizeInput($value);

        if (!self::validateEmpty($value)) {
            $this->errorHandler->addError($field, 'Campo requerido');
            return false;
        }

        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errorHandler->addError($field, 'Debe ser un email valido');
            return false;
        }
        return true;
    }
    /**
     * Valida un campo de role
     *
     * @param string $value Valor del campo de role.
     * @param string $field Nombre del campo.
     * @return bool Retorna true si la validación es exitosa, de lo contrario, false.
     */
    public function validateRole($value, $field = 'role')
    {

        $value = self::sanitizeInput($value);
        if ($value != 1 && $value != 0) {
            $this->errorHandler->addError($field, 'Valor desconocido');
            return false;
        }
        return true;
    }
    /**
     * Funcion para validar un input de Contraseña
     * @param string $value, valor del input contraseña
     */
    public function validatePassword($value, $field = 'password')
    {
        $value = self::sanitizeInput($value);
        if (empty($value)) {
            $this->errorHandler->addError($field, 'Campo requerido');
            return false;
        }
        if (strlen($value) <= 16) {
            $this->errorHandler->addError($field, 'Debe contener mas de 16 caracteres');
            return false;
        }
        if (!preg_match('/[A-Z]/', $value)) {
            $this->errorHandler->addError($field, 'Debe contener carácter en mayúscula');
            return false;
        }
        if (!preg_match('/[a-z]/', $value)) {
            $this->errorHandler->addError($field, 'Debe contener carácter en minúscula');
            return false;
        }
        if (!preg_match('/[0-9]/', $value)) {
            $this->errorHandler->addError($field, 'Debe contener numeros');
            return false;
        }
        if (!preg_match('/[!@#$%^&*(),=.?:{}|<>]/', $value)) {
            $this->errorHandler->addError($field, 'Debe contener al menos uno de estos caracteres !@#$%^&*(),=.?:{}|<>');
            return false;
        }
        return true;
    }
    /**
     * Funcion que los campos password y passwordConfirmation tengan los mismos valores
     * @param string $password, valor del input contraseña 
     * @param string $passwordConfirmation, valor del input de confirmacion de contraseña
     */
    public function validatePasswordConfirmation($password, $passwordConfirmation, $field = 'passwordConfirmation')
    {
        $password = self::sanitizeInput($password);

        if (!self::validateEmpty($passwordConfirmation)) {
            $this->errorHandler->addError($field, 'Campo requerido ');
            return false;
        }
        if ($passwordConfirmation != $password) {
            $this->errorHandler->addError($field, 'Debe conincidir con la contraseña');
            return false;
        }
        return true;
    }
    /**
     * @param int $id, valor del input
     * @param array $listProvinces, array en la que se buscara el id
     */
    public function validateProvinces($id, $listProvinces, $field = 'provinces')
    {
        if ($id == 'null') {
            $this->errorHandler->addError($field, 'Campo obligatorio');
            return false;
        }
        $id = intval($id);
        if (!array_key_exists($id, $listProvinces)) {
            $this->errorHandler->addError($field, 'Valor de provincia desconocido');
            return false;
        }
        return true;
    }
    /**
     * Valida un campo de Estado.
     *
     * @param string $status Valor del campo de Estato.
     * @param string $field Nombre del campo.
     * @return bool Retorna true si la validación es exitosa, de lo contrario, false.
     */
    public function validateStatus($status, $field = 'status')
    {
        if ($status == 'null') {
            $this->errorHandler->addError($field, 'Campo obligatorio');
            return false;
        }
        $status = strtoupper($status);
        $validStates = ['B', 'P', 'R', 'C'];

        if (!in_array($status, $validStates)) {
            $this->errorHandler->addError($field, 'Valor desconocido');
            return false;
        }
        return true;
    }
    /**
     * Valida un campo de Estado.
     *
     * @param string $date Valor del campo de date_task.
     * @param string $field Nombre del campo.
     * @return bool Retorna true si la validación es exitosa, de lo contrario, false.
     */
    public function validateDate($date, $field = 'date_task')
    {
        if (!self::validateEmpty($date)) {
            $this->errorHandler->addError($field, 'Campo requerido ');
            return false;
        }
        $values = explode('-', $date);
        if (count($values) == 3 && checkdate($values[1], $values[2], $values[0])) {
            return true;
        } else {
            $this->errorHandler->addError($field, 'Formato invalido');
            return false;
        }
    }
    /**
     * Valida un campo de Codigo Postal.
     *
     * @param $postalCode Valor del campo de postalCode.
     * @param $provinceId Valor del campo de provincia
     * @param $listProvince array que contiene los 2 primeros digitos de las provincias en la llave ej: [21]=>'Huelva'
     * @param string $field Nombre del campo.
     * @return bool Retorna true si la validación es exitosa, de lo contrario, false.
     */
    public function validatePostalCode($postalCode, $provinceId, $listProvince, $field = 'postalCode')
    {
        $postalCode = self::sanitizeInput($postalCode);

        if (!self::validateEmpty($postalCode)) {
            $this->errorHandler->addError($field, 'Campo requerido');
            return false;
        }

        $provinceCode = substr($postalCode, 0, 2);
        //dd($provinceCode);
        if (!array_key_exists(intval($provinceCode), $listProvince)) {
            $this->errorHandler->addError($field, 'Codigo desconocido');
            return false;
        }
        if ($provinceCode !== $provinceId) {
            $this->errorHandler->addError($field, 'Debe coincidor con la provincia');
            return false;
        }
        if (!preg_match('/^\d{5}([A-Za-z])?$/', $postalCode)) {
            $this->errorHandler->addError($field, 'Formato invalido');
            return false;
        }
        return true;
    }
    public function validatePhoneNumber($phoneNumber, $field = 'phoneNumber')
    {
        if (!self::validateEmpty($phoneNumber)) {
            $this->errorHandler->addError($field, 'Campo requerido');
            return false;
        }
        if (!preg_match('/^(6|7)\d{8}$/', $phoneNumber)) {
            $this->errorHandler->addError($field, 'Formato invalido');
            return false;
        }
        return true;
    }
    public function validateOperario($id, $listOperarios, $field = 'operario')
    {
        if ($id == 'null') {
            $this->errorHandler->addError($field, 'Campo obligatorio');
            return false;
        }

        if (!array_key_exists($id, $listOperarios)) {
            $this->errorHandler->addError($field, 'Valor desconocido');
            return false;
        }
        return true;
    }
    /**
     * Validate DNI (NIF), CIF, NIE
     *
     * @param string $dni Identification number
     *
     * @return bool Whether it is valid (true) or not (false)
     */
    public function validateNifcif($dni, $field = 'nifCif')
    {
        $dni = self::sanitizeInput($dni);

        $cif = strtoupper($dni);
        for ($i = 0; $i < 9; $i++) {
            $num[$i] = substr($cif, $i, 1);
        }
        // If it does not have a valid format, return error
        if (!preg_match('/((^[A-Z]{1}[0-9]{7}[A-Z0-9]{1}$|^[T]{1}[A-Z0-9]{8}$)|^[0-9]{8}[A-Z]{1}$)/', $cif)) {
            $this->errorHandler->addError($field, 'Formato Invalido');
            return false;
        }
        // Standard NIF check
        if (preg_match('/(^[0-9]{8}[A-Z]{1}$)/', $cif)) {
            if ($num[8] == substr('TRWAGMYFPDXBNJZSQVHLCKE', substr($cif, 0, 8) % 23, 1)) {
                return true;
            } else {
                $this->errorHandler->addError($field, 'NIF Invalido');
                return false;
            }
        }
        // Algorithm for CIF codes
        $sum = $num[2] + $num[4] + $num[6];
        for ($i = 1; $i < 8; $i += 2) {
            $sum += (int)substr((2 * $num[$i]), 0, 1) + (int)substr((2 * $num[$i]), 1, 1);
        }
        $n = 10 - substr($sum, strlen($sum) - 1, 1);
        // Special NIFs check (calculated as CIFs or as NIFs)
        if (preg_match('/^[KLM]{1}/', $cif)) {
            if ($num[8] == chr(64 + $n) || $num[8] == substr('TRWAGMYFPDXBNJZSQVHLCKE', substr($cif, 1, 8) % 23, 1)) {
                return true;
            } else {
                $this->errorHandler->addError($field, 'NIF Invalido');
                return false;
            }
        }
        // CIF check
        if (preg_match('/^[ABCDEFGHJNPQRSUVW]{1}/', $cif)) {
            if ($num[8] == chr(64 + $n) || $num[8] == substr($n, strlen($n) - 1, 1)) {
                return true;
            } else {
                $this->errorHandler->addError($field, 'CIF Invalido');
                return false;
            }
        }
        // NIE check
        if (preg_match('/^[T]{1}/', $cif)) {
            if ($num[8] == preg_match('/^[T]{1}[A-Z0-9]{8}$/', $cif)) {
                return true;
            } else {
                $this->errorHandler->addError($field, 'NIE Invalido');
                return false;
            }
        }
        // XYZ
        if (preg_match('/^[XYZ]{1}/', $cif)) {
            if ($num[8] == substr('TRWAGMYFPDXBNJZSQVHLCKE', substr(str_replace(array('X', 'Y', 'Z'), array('0', '1', '2'), $cif), 0, 8) % 23, 1)) {
                return true;
            } else {
                return false;
            }
        }
        // If it has not been verified yet, return error
        return false;
    }
    /**
     * Valida un archivo basado en su tipo de contenido.
     *
     * @param string $archiveMimeType Tipo de contenido del archivo.
     * @param string $mimeType Tipo de contenido esperado ('image' o 'pdf').
     * @param string $field Nombre del campo.
     * @return bool Retorna true si la validación es exitosa, de lo contrario, false.
     */
    public function validateArchive($archiveMimeType, $mimeType, $field)
    {
        if ($mimeType == 'image') {
            $accept = [
                'png' => 'image/png',
                'jpe' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'jpg' => 'image/jpeg',
            ];
            if (!in_array($archiveMimeType, $accept)) {
                $this->errorHandler->addError($field, 'Deber ser una imagen');
                return false;
            }
        }
        if ($mimeType == 'pdf') {
            if ($archiveMimeType != "application/pdf") {
                $this->errorHandler->addError($field, 'Deber ser un PDF');
                return false;
            }
        }
        return true;
    }
    /**
     * Método estático para validar si un valor no está vacío.
     *
     * @param mixed $value Valor a validar.
     * @return bool Retorna true si el valor no está vacío, de lo contrario, false.
     */
    public static function validateEmpty($value)
    {
        if (empty($value) || $value == '' || $value == null) {
            return false;
        }
        return true;
    }
    /**
     * Valida un campo de texto.
     *
     * @param string $value Valor del campo de texto.
     * @param string $field Nombre del campo.
     */
    public function validateText($value, $field)
    {
        $value = self::sanitizeInput($value);

        if (!self::validateEmpty($value))
            $this->errorHandler->addError($field, 'Campo requerido');
    }
}
