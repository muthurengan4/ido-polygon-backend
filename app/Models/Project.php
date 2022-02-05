<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $hidden = ['id','unique_id'];

    protected $appends = ['project_id','project_unique_id', 'username', 'user_displayname','user_picture', 'user_unique_id', 'publish_status_formatted', 'allowed_tokens_formatted', 'total_tokens_formatted', 'total_tokens_purchased_formatted', 'exchange_rate_formatted', 'ido_tokens', 'ido_tokens_formatted'];

    public function getProjectIdAttribute() {

		return $this->id;
	}

	public function getProjectUniqueIdAttribute() {

		return $this->unique_id;
	}

    public function getPublishStatusFormattedAttribute() {

        return projects_publish_status_formatted($this->publish_status);
    }

	public function getUserUniqueIdAttribute() {

		$user_unique_id = $this->user->unique_id ?? "";

		// unset($this->user);

		return $user_unique_id ?? "";
	}

	public function getUsernameAttribute() {

		$username = $this->user->username ?? "";

		// unset($this->user);

		return $username ?? "";
	}

	public function getUserDisplaynameAttribute() {

		$name = $this->user->name ?? "";

		// unset($this->user);

		return $name ?? "";
	}

	public function getUserPictureAttribute() {

		$picture = $this->user->picture ?? "";

		// unset($this->user);

		return $picture ?? "";
	}

    public function getTotalTokensFormattedAttribute() {

        return tokens_formatted($this->total_tokens, $this->token_symbol);
    }

    public function getAllowedTokensFormattedAttribute() {

        return tokens_formatted($this->allowed_tokens, $this->token_symbol);

    }

    public function getTotalTokensPurchasedFormattedAttribute() {

        return tokens_formatted($this->total_tokens_purchased, $this->token_symbol);

    }
    
    public function getExchangeRateFormattedAttribute() {

        return tokens_formatted($this->exchange_rate, $this->token_symbol);

    }

    public function getIdoTokensFormattedAttribute() {

        return ido_tokens_formatted($this->allowed_tokens, $this->exchange_rate);

    }

    public function getIdoTokensAttribute() {

        return ($this->allowed_tokens * $this->exchange_rate) ?? 0;

    }

	public function user() {

	   return $this->belongsTo(User::class, 'user_id');
	}

    public function projectOwnerTransaction() {

       return $this->hasOne(ProjectOwnerTransaction::class, 'project_id');
    }

	/**
     * Scope a query to only include active users.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeApproved($query) {

        $query->where('projects.status', APPROVED);

        return $query;

    }

    /**
     * Scope a query to only include active users.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUpcoming($query) {

        $query->where('projects.publish_status', PROJECT_PUBLISH_STATUS_SCHEDULED)->where('projects.status', APPROVED);

        return $query;

    }

    /**
     * Scope a query to only include active users.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeClosed($query) {

        $query->where('projects.publish_status', PROJECT_PUBLISH_STATUS_CLOSED)->where('projects.status', APPROVED);

        return $query;

    }

    /**
     * Scope a query to only include active users.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOpened($query) {

        $query->where('projects.publish_status', PROJECT_PUBLISH_STATUS_OPENED)->where('projects.status', APPROVED);

        return $query;

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

        static::deleting(function ($model){
            
        });

    }
}
