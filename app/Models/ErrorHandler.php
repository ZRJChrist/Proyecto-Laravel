<?php

namespace App\Models;


class ErrorHandler
{
    private $errors = [];

    public function addError(string $field, string $description): void
    {
        $this->errors[$field] = $description;
    }

    public function hasErrors(): bool
    {
        return empty($this->errors);
    }

    public function hasError(string $field): bool
    {
        return array_key_exists($field, $this->errors);
    }

    public function getError(string $field): string
    {
        return $this->errors[$field] ?? '';
    }
    public function spanError(string $field): string
    {
        if (self::hasError($field)) {
            return '<p class="d-flex mt-0 invalid-feedback"> *' . self::getError($field) . '</p>
            ';
        }
        return '';
    }

    public function getErrorsList(): array
    {
        $errorsList = [];
        foreach ($this->errors as $field => $description) {
            $errorsList[$field] =  $description;
        }
        return $errorsList;
    }
}
