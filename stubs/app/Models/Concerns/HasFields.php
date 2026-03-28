<?php

namespace App\Models\Concerns;

use App\Enums\Field;

trait HasFields
{
    abstract public static function fields(): array;


    public static function getFieldByName(string $name): ?array
    {
        return collect(self::fields())->filter(function ($field) use ($name) {
            return $field['name'] === $name;
        })->values()->toArray()[0] ?? null;
    }

    public static function indexTableHeads(): array
    {
        return collect(self::fields())->filter(function ($field) {
            return isset($field['show-in-index']) && $field['show-in-index'];
        })->map(function ($field) {
            return $field['label'];
        })->values()->toArray();
    }

    public static function indexFieldNames(): array
    {
        return collect(self::fields())->filter(function ($field) {
            return isset($field['show-in-index']) && $field['show-in-index'];
        })->map(function ($field) {
            return $field['name'];
        })->values()->toArray();
    }

    public static function getFileFields(): array
    {
        return collect(self::fields())->filter(function ($field) {
            return isset($field['type']) && $field['type'] == Field::FILE;
        })->values()->toArray();
    }

    public static function getFileFieldNames(): array
    {
        return collect(self::fields())->filter(function ($field) {
            return isset($field['type']) && $field['type'] == Field::FILE;
        })->values()->map(function ($field) {
            return $field['name'];
        })->toArray();
    }

    public static function getFillableFields(): array
    {
        return collect(self::fields())->filter(function ($field) {
            return isset($field['type']) && $field['type'] !== Field::FILE;
        })->values()->toArray();
    }
    public static function getFillableFieldNames(): array
    {
        return collect(self::fields())->filter(function ($field) {
            return isset($field['type']) && $field['type'] !== Field::FILE;
        })->values()->map(function ($field) {
            return $field['name'];
        })->toArray();
    }

    public static function filterFields(): array
    {
        return collect(self::fields())->filter(function ($field) {
            return isset($field['filterable']) && $field['filterable'];
        })->values()->toArray();
    }

    public static function filterFieldNames(): array
    {
        return collect(self::filterFields())->map(function ($field) {
            return $field['name'];
        })->values()->toArray();
    }

    public static function fieldNames(): array
    {
        return array_map(function ($field) {
            return $field['name'];
        }, self::fields());
    }

    public static function fieldLabels(): array
    {
        return array_map(function ($field) {
            return $field['label'];
        }, self::fields());
    }

    public static function getInput(string $name, string $variant = 'web'): ?string
    {
        $field = self::getFieldByName($name);

        if (! $field) return null;

        return $field['type']->input($field, $variant);
    }

    public function getFieldValue(string $name): mixed
    {
        if ($field = self::getFieldByName($name)) {
            return $field['type']->getValue($this->{$field['name']});
        }

        return $this->{$field['name']};
    }
}
