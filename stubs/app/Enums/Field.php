<?php

namespace App\Enums;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;

enum Field: string
{
    case STRING = 'string';
    case TEXT = 'text';
    case EMAIL = 'email';
    case PHONE = 'phone';
    case CHECKBOX = 'checkbox';
    case RADIO = 'radio';
    case SELECT = 'select';
    case YES_NO = 'yes-no';

    public function migration(Blueprint $table, string $name)
    {
        return match ($this) {
            self::STRING, self::EMAIL, self::PHONE, self::RADIO, self::YES_NO, self::SELECT => $table->string($name)->nullable(),
            self::TEXT => $table->text($name)->nullable(),
            self::CHECKBOX => $table->boolean($name)->nullable(),
        };
    }

    public function input(?array $field = null, string $variant = 'web', ?Model $model = null): ?string
    {
        if (! $field) {
            return null;
        }

        return view("components.form.$variant.".$this->value, compact('field', 'model'))->render();
    }

    public function getValue(string $name, mixed $value): string
    {
        return match ($this) {
            self::YES_NO => filter_var($value, FILTER_VALIDATE_BOOLEAN) ? 'Yes' : 'No',
            default => $value,
        };
    }
}
