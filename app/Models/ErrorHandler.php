<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErrorHandler extends Model
{
    use HasFactory;
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

    public function getErrorsList(): array
    {
        $errorsList = [];
        foreach ($this->errors as $field => $description) {
            $errorsList[$field] =  $description;
        }
        return $errorsList;
    }
}
