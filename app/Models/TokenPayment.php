<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TokenPayment extends Model
{
    use HasFactory;

    protected $hidden = ['deleted_at', 'id', 'unique_id'];

    protected $appends = ['token_payment_id', 'token_payment_unique_id','username', 'user_displayname','user_picture', 'user_unique_id','purchased_formatted'];

    public function getTokenPaymentIdAttribute() {

        return $this->id;
    }

    public function getTokenPaymentUniqueIdAttribute() {

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

        return formatted_amount($this->purchased);
    }
    
    public function user() {

       return $this->belongsTo(User::class, 'user_id');
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
