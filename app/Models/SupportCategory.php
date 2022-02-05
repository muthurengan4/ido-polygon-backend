<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportCategory extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'picture'];

    protected $hidden = ['id', 'unique_id'];

    protected $appends = ['support_category_id', 'support_category_unique_id'];
    
    public function getSupportCategoryIdAttribute() {

        return $this->id;
    }

    public function getSupportCategoryUniqueIdAttribute() {

        return $this->unique_id;
    }

    public static function boot() {

        parent::boot();

        static::creating(function ($model) {

            $model->attributes['unique_id'] = "CA"."-".uniqid();

        });

        static::created(function($model) {

            $model->attributes['unique_id'] = "CA"."-".$model->attributes['id']."-".uniqid();

            $model->save();
        
        });

    }
}
