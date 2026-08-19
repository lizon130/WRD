<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Segmentation extends Model
{
    use HasFactory;

    protected $table = 'segmentations';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->generateSlug();
        });

        static::deleting(function ($segment) {
            // Recursively delete all children segments
            $segment->translations()->delete();
            $segment->children->each(function ($child) {
                $child->delete();  // This will trigger the 'deleting' event for each child
            });
        });
    }

    public function parent(){
        return $this->belongsTo(Segmentation::class, 'ancestor_id', 'id');
    }

    public function children(){
        return $this->hasMany(Segmentation::class, 'ancestor_id', 'id');
    }

    public function translations()
    {
        return $this->morphMany(Translation::class, 'translatable');
    }

    public function getTranslation($languageCode, $field)
    {
        return $this->translations()
            ->where('language_code', $languageCode)
            ->where('field', $field)
            ->value('value') ?? $this->$field;
    }

    protected function generateSlug(){
        $slug = Str::slug($this->name);

        $count = static::where('slug', $slug)->where('id', '!=', $this->id)->count();
        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }
        $this->slug = $slug;
    }
}
