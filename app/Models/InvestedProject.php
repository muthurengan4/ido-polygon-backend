<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestedProject extends Model
{
    use HasFactory;

     protected $hidden = ['id', 'unique_id'];

    protected $appends = ['invested_project_id', 'invested_project_unique_id','username', 'user_displayname','user_picture', 'user_unique_id', 'project_name', 'purchased_formatted', 'claim_payment_status_formatted'];

    public function getInvestedProjectIdAttribute() {

        return $this->id;
    }

    public function getInvestedProjectUniqueIdAttribute() {

        return $this->unique_id;
    }

    public function getUserUniqueIdAttribute() {

        $user_unique_id = $this->user->unique_id ?? "";

        unset($this->user);

        return $user_unique_id ?? "";
    }

    public function getUsernameAttribute() {

        $user_name = $this->user->name ?? "";

        unset($this->user);

        return $user_name ?? "";
    }

    public function getProjectNameAttribute() {

        $project_name = $this->project->name ?? "";

        unset($this->project_name);

        return $project_name ?? "";
    }

    public function getUserDisplaynameAttribute() {

        $name = $this->user->name ?? "";

        unset($this->user);

        return $name ?? "";
    }

    public function getUserPictureAttribute() {

        $picture = $this->user->picture ?? "";

        unset($this->user);

        return $picture ?? "";
    }

    public function getPurchasedFormattedAttribute() {

        return tokens_formatted($this->purchased, $this->project->token_symbol ?? "");
    }

    public function getClaimPaymentStatusFormattedAttribute() {

        return claim_payment_status_formatted($this->claim_payment_status);
    }


    public function user() {

       return $this->belongsTo(User::class, 'user_id');
    }

    public function project() {

       return $this->belongsTo(Project::class, 'project_id');
    }

    /**
     * Scope a query to basic subscription details
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeBaseResponse($query) {

        return $query;
    }

    public static function boot() {

        parent::boot();

        static::creating(function ($model) {

            $model->attributes['unique_id'] = "SP-"."-".uniqid();
        });

        static::created(function($model) {

            $model->attributes['unique_id'] = "SP-"."-".$model->attributes['id']."-".uniqid();

            $model->save();
        
        });

    }
}
