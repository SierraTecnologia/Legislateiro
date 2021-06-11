<?php

namespace Legislateiro\Models;

use Carbon\Carbon;
use Exception;
use Legislateiro\Traits\UsedByTeams;

class Term extends BaseModel
{

    public const RULES = [
        'name'=>'required',
        // 'group_qty' => 'required|integer'
    ];
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'playlist_id',
    ];

    protected $mappingProperties = array(

        'name' => [
            'type' => 'string',
            "analyzer" => "standard",
        ],
        'description' => [
            'type' => 'string',
            "analyzer" => "standard",
        ],
        'playlist_id' => [
            'type' => 'id',
            "analyzer" => "standard",
        ],

    );
    
    public function playlist()
    {
        return $this->belongsTo('Legislateiro\Models\Playlist', 'playlist_id', 'id');
    }
    /**
     * Get the tokens record associated with the user.
     */
    public function computers()
    {
        return $this->hasMany('Legislateiro\Models\Computer', 'group_id', 'id');
    }


}