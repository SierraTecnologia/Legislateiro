<?php

namespace Legislateiro\Models;

use Carbon\Carbon;
use Exception;
use Legislateiro\Traits\UsedByTeams;
use Pedreiro\Models\Base;

class ParteType extends Base
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
        'members',
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
        'members' => [
            'type' => 'string',
            "analyzer" => "standard",
        ],

    );
    
    protected $formFields = [
        [
            'name' => 'name',
            'label' => 'Name',
            'type' => 'text'
        ],
        [
            'name' => 'description',
            'label' => 'description',
            'type' => 'text'
        ],
        [
            'name' => 'members',
            'label' => 'members',
            'type' => 'text'
        ],
    ];
    public $indexFields = [
        'name',
        'description',
        'members'
    ];

    /**
     * 
     */
    public function terms()
    {
        return $this->hasMany('Legislateiro\Models\Term', 'term_id', 'id');
    }


}