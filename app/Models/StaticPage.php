<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class StaticPage extends Model
{
    use HasFactory;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['id', 'unique_id'];

    protected $appends = ['static_page_id', 'static_page_unique_id'];

    public function getStaticPageIdAttribute() {

        return $this->id;
    }

    public function getStaticPageUniqueIdAttribute() {

        return $this->unique_id;
    }
}
