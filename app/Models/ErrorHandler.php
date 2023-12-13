<?php

namespace App\Models;

/**
 * Autor: @ZRJChrist
 *
 * Descripción: Clase que maneja errores y validaciones.
 *  
 * Fecha de creación: 23/11/2023
 * 
 */
class ErrorHandler
{
    // Almacena los errores como un array asociativo de campo => descripción del error.
    private $errors = [];

    /**
     * Agrega un error al manejador de errores.
     *
     * @param string $field Campo asociado al error.
     * @param string $description Descripción del error.
     * @return void
     */
    public function addError(string $field, string $description): void
    {
        $this->errors[$field] = $description;
    }

    /**
     * Verifica si hay errores almacenados en el manejador.
     *
     * @return bool Retorna verdadero si hay errores, de lo contrario, falso.
     */
    public function hasErrors(): bool
    {
        return empty($this->errors);
    }

    /**
     * Verifica si hay un error asociado a un campo específico.
     *
     * @param string $field Campo a verificar.
     * @return bool Retorna verdadero si hay un error asociado al campo, de lo contrario, falso.
     */
    public function hasError(string $field): bool
    {
        return array_key_exists($field, $this->errors);
    }

    /**
     * Obtiene la descripción de un error asociado a un campo específico.
     *
     * @param string $field Campo asociado al error.
     * @return string Retorna la descripción del error o una cadena vacía si no hay error asociado al campo.
     */
    public function getError(string $field): string
    {
        return $this->errors[$field] ?? '';
    }

    /**
     * Retorna una cadena HTML formateada para mostrar un error asociado a un campo en un elemento span.
     *
     * @param string $field Campo asociado al error.
     * @return string Retorna una cadena HTML con el formato para mostrar el error.
     */
    public function spanError(string $field): string
    {
        if (self::hasError($field)) {
            return '<p class="d-flex mt-0 invalid-feedback"> *' . self::getError($field) . '</p>';
        }
        return '';
    }

    /**
     * Obtiene todos los errores como un array asociativo de campo => descripción del error.
     *
     * @return array Retorna un array asociativo de errores.
     */
    public function getErrorsList(): array
    {
        $errorsList = [];
        foreach ($this->errors as $field => $description) {
            $errorsList[$field] =  $description;
        }
        return $errorsList;
    }
}
