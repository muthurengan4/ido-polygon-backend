<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    protected $fillable = ['question', 'answer'];

    protected $hidden = ['id', 'unique_id'];

    protected $appends = ['faq_id', 'faq_unique_id'];
    
    public function getFaqIdAttribute() {

        return $this->id;
    }

    public function getFaqUniqueIdAttribute() {

        return $this->unique_id;
    }

    public static function boot() {

        parent::boot();

        static::creating(function ($model) {

            $model->attributes['unique_id'] = "FAQ"."-".uniqid();

        });

        static::created(function($model) {

            $model->attributes['unique_id'] = "FAQ"."-".$model->attributes['id']."-".uniqid();

            $model->save();
        
        });

    }
}
