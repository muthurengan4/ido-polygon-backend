<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectPayment extends Model
{
    use HasFactory;

    protected $hidden = ['id','unique_id'];

    protected $appends = ['project_payment_id','project_payment_unique_id'];

    public function getProjectPaymentIdAttribute() {

        return $this->id;
    }

    public function getProjectPaymentUniqueIdAttribute() {

        return $this->unique_id;
    }

    public function project() {

       return $this->belongsTo(Project::class, 'project_id');
    }

    public function user() {

       return $this->belongsTo(User::class, 'user_id');
    }

    public static function boot() {

        parent::boot();

        static::creating(function ($model) {
            $model->attributes['unique_id'] = "P"."-".uniqid();
        });

        static::created(function($model) {

            $model->attributes['unique_id'] = "P"."-".$model->attributes['id']."-".uniqid();

            $model->save();
        
        });

        static::deleting(function ($model) {            
        
        });

    }
}
