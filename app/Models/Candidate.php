<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords($value);
    }

    public static function getAllCandidate():array{
        return self::all()->pluck('name')->toArray();
    }

    public static function getAllCandidateVotes():array{
        return self::all()->pluck('votes')->toArray();
    }


}
