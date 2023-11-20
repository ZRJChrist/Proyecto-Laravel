<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Validator;

class ValidatorTest extends TestCase
{
    public function testNombreValido()
    {
        $nombresValidos = [
            'a',
            'Christopher Joseph',
            'Maria Fernanda',
            'Alice Johnson',
            'Carlos García',
            'Elena Rodríguez',
            'David Smith',
            'Laura Pérez',
        ];

        foreach ($nombresValidos as $nombre) {
            $resultado = Validator::validateName($nombre);
            $this->assertTrue($resultado, "El nombre '$nombre' debería ser válido.");
        }
    }

    public function testNombreInvalido()
    {
        $nombresInvalidos = [
            '',
            '123',            // Números no permitidos
            'John123',        // Números no permitidos
            'Nombre#Invalido', // Caracteres especiales no permitidos
            'Carlos123 García',  // Números no permitidos
            'David@Smith',
            'David Smith\'asd',
            'Laura Pérez\'',
        ];

        foreach ($nombresInvalidos as $nombre) {
            $resultado = Validator::validateName($nombre);
            $this->assertFalse($resultado, "El nombre '$nombre' debería ser inválido.");
        }
    }
    public function testContrasenaValida()
    {
        $contrasenasValidas = [
            'ContrasenaFuerte123!',
            'OtraContrasenaSegura%1',
            '12345AaBbC!@asdaASD',
        ];

        foreach ($contrasenasValidas as $contrasena) {
            $resultado = Validator::validatePassword($contrasena);
            $this->assertTrue($resultado, "La contraseña '$contrasena' debería ser válida.");
        }
    }

    public function testContrasenaInvalida()
    {
        $contrasenasInvalidas = [
            '',                    // Cadena vacía
            'Corta123!',           // Menos de 16 caracteres
            'sinmayusculas123!',   // Sin mayúsculas
            'SINMINUSCULAS123!',   // Sin minúsculas
            'SoloLetrasAbc',       // Sin números ni caracteres especiales
            '!@#$%^&*(),=.?:{}<>', // Sin mayúsculas
        ];

        foreach ($contrasenasInvalidas as $contrasena) {
            $resultado = Validator::validatePassword($contrasena);
            $this->assertFalse($resultado, "La contraseña '$contrasena' debería ser inválida.");
        }
    }
    public function testEmailValido()
    {
        $emailsValidos = [
            'usuario@example.com',
            'nombre.apellido@dominio.com',
            'correo_valido123@subdominio.dominio.net',
        ];

        foreach ($emailsValidos as $email) {
            $resultado = Validator::validateEmail($email);
            $this->assertTrue($resultado, "El email '$email' debería ser válido.");
        }
    }

    public function testEmailInvalido()
    {
        $emailsInvalidos = [
            '',                        // Cadena vacía
            'correo_sin_arroba.com',   // Sin símbolo '@'
            'correo@sin_punto_com',    // Sin punto después del '@'
            'correo@dominio_sin_punto', // Sin punto después del dominio
            'correo@dominio.123',       // Números no permitidos en el dominio
            'correo@dominio.com@extra', // Símbolo '@' adicional
            'correo@dominio_.com',       // Caracter '_' no permitido
        ];

        foreach ($emailsInvalidos as $email) {
            $resultado = Validator::validateEmail($email);
            $this->assertFalse($resultado, "El email '$email' debería ser inválido.");
        }
    }
}
