<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchEngineRequest extends Model
{
    use HasFactory;

    protected $guarded =  [];

    public function scopeKnownKeywords($query, $keywords)
    {
        $query->where('keywords_sorted', json_encode($keywords))
            ->where('successful', true)->orderBy('created_at', 'desc');
    }
}
