<?php

namespace App\Models;

use AhmedAliraqi\LaravelFilterable\Filterable;
use App\Emails\Concerns\HasEmailTemplate;
use App\Emails\Contracts\HasEmailTemplateContract;
use App\Enums\Field;
use App\Excel\Exportable;
use App\Excel\Importable;
use App\Http\Filters\__CRUD_STUDLY_SINGULAR__Filter;
use App\Models\Concerns\HasFields;
use App\Support\Traits\Selectable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class __CRUD_STUDLY_SINGULAR__ extends Model implements HasEmailTemplateContract, Exportable, Importable, HasMedia
{
    use Filterable;
    use HasFactory;
    use Selectable;
    use HasEmailTemplate;
    use HasFields;
    use InteractsWithMedia;

    /**
     * The filter class used for querying this model.
     *
     * @var class-string<__CRUD_STUDLY_SINGULAR__Filter>
     */
    protected string $filter = __CRUD_STUDLY_SINGULAR__Filter::class;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    public static function fields(): array
    {
        return [
            [
                'name' => 'name',
                'label' => 'Name',
                'type' => Field::STRING,
                'show-in-index' => true,
                'icon' => 'fa fa-user-circle',
                'rules' => ['required'],
            ],
        ];
    }

    public function emailReplacements(): array
    {
        $replacements = [];

        $model = str(class_basename($this))->snake()->upper()->toString();

        foreach (self::fields() as $field) {
            $value = is_object($this->{$field['name']}) && enum_exists(get_class($this->{$field['name']}))
                ? $this->{$field['name']}->value : $this->{$field['name']};

            $key = '%'.$model.'_'.strtoupper($field['name']).'%';

            $replacements[$key] = $value;
        }

        return $replacements;
    }

    public static function toExcelRows(Collection $models): Collection
    {
        return $models;
    }

    public static function makeFromExcel(array $row): static|array|null
    {
        $data = collect(self::getFillableFields())->mapWithKeys(function ($field) use ($row) {
            return [$field['name'] => data_get($row, $field['name'])];
        })->toArray();

        $model = new self;

        return $model->forceFill($data);
    }

    public static function validationRules(): array
    {
        return collect(self::getFillableFields())->mapWithKeys(function ($field) {
            return [$field['name'] => $field['rules'] ?? []];
        })->toArray();
    }

    public static function uniqueBy(): string
    {
        return 'email';
    }

    /**
     * Define the media collections.
     */
    public function registerMediaCollections(): void
    {
        foreach (self::getFileFields() as $field) {
            $collection = $this->addMediaCollection($field['name']);

            if (isset($field['single-file']) && $field['single-file']) {
                $collection->singleFile();
            }

            $collection->registerMediaConversions(function () {
                $this
                    ->addMediaConversion('large')
                    ->format('webp')
                    ->quality(60);

                $this
                    ->addMediaConversion('medium')
                    ->fit(Fit::Max, 800, 800)
                    ->format('webp')
                    ->quality(60);

                $this
                    ->addMediaConversion('small')
                    ->fit(Fit::Max, 400, 400)
                    ->format('webp')
                    ->quality(60);

                $this
                    ->addMediaConversion('thumb')
                    ->fit(Fit::Max, 150, 150)
                    ->format('webp')
                    ->quality(60);
            });
        }
    }
}
