<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    use HasFactory;

    protected $fillable = ['support_category_id', 'question', 'message'];

    protected $hidden = ['id', 'unique_id'];

    protected $appends = ['support_ticket_id', 'support_ticket_unique_id'];
    
    public function getSupportTicketIdAttribute() {

        return $this->id;
    }

    public function getSupportTicketUniqueIdAttribute() {

        return $this->unique_id;
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
