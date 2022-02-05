<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Helpers\Helper;

use Setting;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $appends = ['user_id', 'user_unique_id'];
    
    public function getUserIdAttribute() {

        return $this->id;
    }

    public function getUserUniqueIdAttribute() {

        return $this->unique_id;
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'id', 'unique_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Scope a query to only include active users.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeApproved($query) {

        $query->where('users.status', USER_APPROVED)->where('is_email_verified', USER_EMAIL_VERIFIED);

        return $query;

    }

    public function userCards() {

        return $this->hasMany(UserCard::class);
    }

    public function userDocuments() {

        return $this->hasMany(UserDocument::class);
    }

    public function subscriptionPayments() {

        return $this->hasMany(SubscriptionPayment::class);
    }

    public function investedProjects() {

        return $this->hasMany(InvestedProject::class);
    }

    public function projects() {

        return $this->hasMany(Project::class);
    }

    public function projectPayments() {

        return $this->hasMany(ProjectPayment::class);
    }

    public function tokenPayments() {

        return $this->hasMany(TokenPayment::class);
    }

    public function projectOwnerTransactions() {

        return $this->hasMany(ProjectOwnerTransaction::class);
    }
    
    public static function boot() {

        parent::boot();

        static::creating(function ($model) {

            $model->attributes['token'] = Helper::generate_token();

            $model->attributes['token_expiry'] = Helper::generate_token_expiry();

            $model->attributes['status'] = USER_APPROVED;

             if (Setting::get('is_account_email_verification') == YES && envfile('MAIL_USERNAME') && envfile('MAIL_PASSWORD')) { 

                $model->generateEmailCode();            

            } else {
                
                $model->attributes['is_email_verified'] = USER_EMAIL_VERIFIED;

            }

        });

        static::created(function($model) {
            
            $model->attributes['is_email_notification'] = $model->attributes['is_push_notification'] = YES;

            $model->attributes['unique_id'] = routefreestring(strtolower($model->attributes['name'] ?: rand(1,10000).rand(1,10000))).'-'.$model->attributes['id'];

            $model->save();
        
        });

        static::updating(function($model) {

        });

        static::deleting(function ($model){

            Helper::storage_delete_file($model->picture, PROFILE_PATH_USER);

            $model->userCards()->delete();

            $model->userDocuments()->delete();

            $model->subscriptionPayments()->delete();

            foreach ($model->projects as $key => $project) {
                $project->delete();
            }

            $model->projectPayments()->delete();

            $model->tokenPayments()->delete();

            $model->projectOwnerTransactions()->delete();

        });

    }

    /**
     * Generates Token and Token Expiry
     * 
     * @return bool returns true if successful. false on failure.
     */

    protected function generateEmailCode() {

        $this->attributes['verification_code'] = Helper::generate_email_code();

        $this->attributes['verification_code_expiry'] = Helper::generate_email_expiry();

        return true;
    
    }
}
