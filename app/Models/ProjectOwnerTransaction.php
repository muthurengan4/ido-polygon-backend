<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectOwnerTransaction extends Model
{
    use HasFactory;

    protected $hidden = ['id','unique_id'];

    protected $appends = ['project_owner_transaction_id','project_owner_transaction_unique_id'];

    public function getProjectOwnerTransactionIdAttribute() {

        return $this->id;
    }

    public function getProjectOwnerTransactionUniqueIdAttribute() {

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
