<?php

namespace App\Models;


class Validator
{
    private $errorHandler;
    public function __construct()
    {
        $this->errorHandler = new ErrorHandler;
    }
    public  function sanitizeInput($data)
    {
        $data = trim($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    public function getErrors()
    {
        return $this->errorHandler->getErrorsList();
    }
    public function hasErrors()
    {
        return $this->errorHandler->hasErrors();
    }
    public  function validateName($value, $field = 'name')
    {
        $value = Validator::sanitizeInput($value);
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

    public function validateEmail($value, $field = 'email')
    {
        $value = Validator::sanitizeInput($value);
        if (empty($value) || $value == '' || $value == null) {
            $this->errorHandler->addError($field, 'Campo requerido');
            return false;
        }
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errorHandler->addError($field, 'Debe ser un email valido');
            return false;
        }
        return true;
    }
    public function validatePassword($value, $field = 'password')
    {
        $value = Validator::sanitizeInput($value);
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
    public function validatePasswordConfirmation($password, $passwordConfirmation, $field = 'passwordConfirmation')
    {
        if ($passwordConfirmation != $password) {
            $this->errorHandler->addError($field, 'Debe conincidir con la contraseña');
            return false;
        }
        return true;
    }
}
