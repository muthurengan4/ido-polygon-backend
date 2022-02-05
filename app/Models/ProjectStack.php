<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectStack extends Model
{
    use HasFactory;

    protected $hidden = ['deleted_at', 'id', 'unique_id'];

    protected $appends = ['project_stack_id', 'project_stack_unique_id', 'stacked_formatted', 'unstacked_formatted'];

    public function getProjectStackIdAttribute() {

        return $this->id;
    }

    public function getProjectStackUniqueIdAttribute() {

        return $this->unique_id;
    }

    public function getStackedFormattedAttribute() {

        return formatted_amount($this->stacked);
    }

    public function getUnstackedFormattedAttribute() {

        return formatted_amount($this->unstacked);
    }
    
    public function user() {

       return $this->belongsTo(User::class, 'user_id');
    }

    public function project() {

       return $this->belongsTo(Project::class, 'project_id');
    }

    public static function boot() {

        parent::boot();

        static::creating(function ($model) {
            $model->attributes['unique_id'] = "TP"."-".uniqid();
        });

        static::created(function($model) {

            $model->attributes['unique_id'] = "TP"."-".$model->attributes['id']."-".uniqid();

            $model->save();
        
        });

    }
}
