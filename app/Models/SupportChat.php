<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupportChat extends Model
{
    use HasFactory;

    protected $fillable = ['support_ticket_id', 'message'];

    protected $hidden = ['id', 'unique_id'];

    protected $appends = ['support_chat_id', 'support_chat_unique_id'];
    
    public function getSupportChatIdAttribute() {

        return $this->id;
    }

    public function getSupportChatUniqueIdAttribute() {

        return $this->unique_id;
    }

    public function user() {

        return $this->belongsTo(User::class, 'user_id');
    }

    public function supportTicket() {

        return $this->belongsTo(SupportTicket::class);
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
