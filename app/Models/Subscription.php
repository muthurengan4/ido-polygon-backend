<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Helpers\Helper;


class Subscription extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'description', 'min_staking_balance', 'allowed_tokens'];

    protected $hidden = ['id', 'unique_id'];

    protected $appends = ['subscription_id', 'subscription_unique_id'];

    public function getSubscriptionIdAttribute() {

        return $this->id;
    }

    public function getSubscriptionUniqueIdAttribute() {

        return $this->unique_id;
    }

    public function subscriptionPayments() {

        return $this->hasMany(SubscriptionPayment::class,'subscription_id');
    }

    /**
     * Scope a query to only include approved records.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeApproved($query) {

        return $query->where('subscriptions.status', APPROVED);
    
    }

   
    public function getTotalSubscribersAttribute() {

        return $this->subscriptionPayments ? $this->subscriptionPayments->count() : 0;
    }

    public static function boot() {

        parent::boot();

        static::creating(function ($model) {

            $model->attributes['unique_id'] = "SI-"."-".uniqid();
        });

        static::created(function($model) {

            $model->attributes['unique_id'] = "SI-"."-".$model->attributes['id']."-".uniqid();

            $model->save();
        
        });

        static::deleting(function ($model){

            Helper::storage_delete_file($model->picture, COMMON_FILE_PATH);
            
        });
    }
}
