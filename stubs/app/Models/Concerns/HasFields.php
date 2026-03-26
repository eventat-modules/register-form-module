<?php

namespace App\Models\Concerns;

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
            return $field['show-in-index'];
        })->map(function ($field) {
            return $field['label'];
        })->values()->toArray();
    }

    public static function indexFieldNames(): array
    {
        return collect(self::fields())->filter(function ($field) {
            return $field['show-in-index'];
        })->map(function ($field) {
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
        return $this->{$name};
    }
}
