<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPayment extends Model
{
    use HasFactory;

    protected $hidden = ['id', 'unique_id'];

    protected $appends = ['subscription_payment_id', 'subscription_payment_unique_id', 'plan_formatted', 'amount_formatted', 'currency', 'status_formatted', 'subscription_name'];

    public function getSubscriptionPaymentIdAttribute() {

        return $this->id;
    }

    public function getSubscriptionPaymentUniqueIdAttribute() {

        return $this->unique_id;
    }

    public function getPlanFormattedAttribute() {

        return formatted_plan($this->plan, $this->plan_type);
    }

    public function getAmountFormattedAttribute() {

        return formatted_amount($this->amount);
    }

    public function getCurrencyAttribute() {

        return \Setting::get('currency' , '$');
    }

    public function getStatusFormattedAttribute() {

        return $this->status == PAID ? tr('paid') : tr('unpaid');
    }

    public function getSubscriptionNameAttribute() {

        $name = $this->subscription->title ?? tr('unavailable');

        unset($this->subscription);

        return $name;
    }

    /**
     * Get the subscription details 
     */
    public function subscription() {

        return $this->belongsTo(Subscription::class,'subscription_id');

    }

    /**
     * Get the subscription details 
     */
    public function user() {

        return $this->belongsTo(User::class,'user_id');

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
