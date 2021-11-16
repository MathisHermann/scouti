<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class SearchEngineResult extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function searchEngineRequests()
    {
        return $this->belongsTo(SearchEngineRequest::class);
    }

    public static function lastRequests($limit = 3)
    {
        return DB::table('search_engine_results')->whereRaw('	search_engine_requests_id > (
		SELECT
			MAX(search_engine_requests_id)
		FROM
			search_engine_results) - ' . $limit);
    }

    public function scopeFromLastRequests($query, $limit = 3)
    {
        return $query->whereHas('searchEngineRequests', function (Builder $query) use ($limit) {
            $query->orderBy('search_engine_requests_id', 'desc')->take($limit);
        });

        /*
         * select
  *
from
  `search_engine_results`
JOIN
(select id from search_engine_requests order by id desc limit 3) as v2 on search_engine_results.search_engine_requests_id = v2.id
         */
    }

}
