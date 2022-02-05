<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactForm extends Model
{
    use HasFactory;

    protected $hidden = ['id', 'unique_id'];

    protected $appends = ['contact_form_id', 'contact_form_unique_id'];
    
    public function getContactFormIdAttribute() {

        return $this->id;
    }

    public function getContactFormUniqueIdAttribute() {

        return $this->unique_id;
    }

    public static function boot() {

        parent::boot();

        static::creating(function ($model) {

            $model->attributes['unique_id'] = "CF"."-".uniqid();

        });

        static::created(function($model) {

            $model->attributes['unique_id'] = "CF"."-".$model->attributes['id']."-".uniqid();

            $model->save();
        
        });

    }
}
