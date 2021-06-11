<?php

namespace Legislateiro\Http\Controllers\Admin;

use Legislateiro\Models\ParteType;
use Pedreiro\CrudController;

class ParteTypeController extends Controller
{
    use CrudController;

    public function __construct(ParteType $model)
    {
        $this->model = $model;
        parent::__construct();
    }
}
