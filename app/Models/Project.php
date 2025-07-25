<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ["name", "desc", "company_id"];
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
