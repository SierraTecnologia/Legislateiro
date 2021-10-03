<?php

namespace Legislateiro\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\Builder;
// use Elasticquent\ElasticquentTrait;
use Illuminate\Support\Facades\Hash;
use Legislateiro\Util\Business;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * @todo Ver Business Aqui
 */
class Model extends EloquentModel
{

    public function hasAttribute($attr): bool
    {
        return array_key_exists($attr, $this->attributes);
    }

    public static function boot()
    {
        parent::boot();
        return;
        // //@todo Mudar
        // if (\App::runningInConsole() ) {
        //     Log::notice('Rodando em Console');
        //     return null;
        // }

        // if (isset(static::$organizationPerspective) && static::$organizationPerspective) {

        //     if(!$user = Auth::user()) {
        //         $user = Business::getBusinessUser();
        //     }

        //     if (!$user) {
        //         return ;
        //     }

        //     if (!$user->isAdmin() || !Auth::check()) {
        //         static::addGlobalScope(
        //             'user', function (Builder $builder) use ($user) {
        //                 $builder->where(self::getTableName().'.user_id', '=', $user->id);
        //             }
        //         );
        //     }

        //     self::creating(
        //         function ($model) use ($user) {
        //             if (!$model->hasAttribute('user_id')) {
        //                 Log::notice('Modelo '.self::getTableName().' é do tipo perspectiva da organização e nao possui campo user_id');
        //                 return ;
        //             }
        //             if (empty($model->user_id)) {
        //                 $model->user_id = $user->id;
        //             }
        //         }
        //     );

        // }

    }
}