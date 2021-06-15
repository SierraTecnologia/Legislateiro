<?php

namespace Legislateiro\Models;

use Carbon\Carbon;
use Exception;
use Legislateiro\Traits\UsedByTeams;
use Pedreiro\Models\Base;

class TermStage extends Base
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

    );
    
    /**
     * 
     */
    public function terms()
    {
        return $this->hasMany('Legislateiro\Models\Term', 'term_id', 'id');
    }


}