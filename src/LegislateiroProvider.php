<?php

namespace Legislateiro;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use Legislateiro\Services\LegislateiroService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\View;

use Log;
use App;
use Config;
use Route;
use Illuminate\Routing\Router;

use Muleta\Traits\Providers\ConsoleTools;

use Legislateiro\Facades\Legislateiro as LegislateiroFacade;
use Illuminate\Contracts\Events\Dispatcher;


class LegislateiroProvider extends ServiceProvider
{
    use ConsoleTools;

    public static $aliasProviders = [
        'Legislateiro' => \Legislateiro\Facades\Legislateiro::class,
    ];

    public static $providers = [

        \Support\SupportProviderService::class,

        
    ];

    /**
     * Rotas do Menu
     */
    public static $menuItens = [
        [
            'text' => 'Legislateiro',
            'icon' => 'fas fa-fw fa-search',
            'icon_color' => "blue",
            'label_color' => "success",
            'level'       => 3, // 0 (Public), 1, 2 (Admin) , 3 (Root)
        ],
        'Legislateiro' => [
            [
                'text'        => 'Procurar',
                'icon'        => 'fas fa-fw fa-search',
                'icon_color'  => 'blue',
                'label_color' => 'success',
                'level'       => 3, // 0 (Public), 1, 2 (Admin) , 3 (Root)
                // 'access' => \App\Models\Role::$ADMIN
            ],
            'Procurar' => [
                [
                    'text'        => 'Projetos',
                    'route'       => 'rica.legislateiro.projetos.index',
                    'icon'        => 'fas fa-fw fa-ship',
                    'icon_color'  => 'blue',
                    'label_color' => 'success',
                    'level'       => 3, // 0 (Public), 1, 2 (Admin) , 3 (Root)
                    // 'access' => \App\Models\Role::$ADMIN
                ],
            ],
        ],
    ];

    /**
     * Alias the services in the boot.
     */
    public function boot()
    {
        
        // Register configs, migrations, etc
        $this->registerDirectories();

        // COloquei no register pq nao tava reconhecendo as rotas para o adminlte
        $this->app->booted(
            function () {
                $this->routes();
            }
        );

        $this->loadLogger();
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        /**
         * Legislateiro; Routes
         */
        Route::group(
            [
                'namespace' => '\Legislateiro\Http\Controllers',
                'prefix' => \Illuminate\Support\Facades\Config::get('application.routes.main', ''),
                'as' => 'rica.',
                // 'middleware' => 'rica',
            ], function ($router) {
                include __DIR__.'/../routes/web.php';
            }
        );
    }

    /**
     * Register the services.
     */
    public function register()
    {
        $this->mergeConfigFrom($this->getPublishesPath('config/sitec/legislateiro.php'), 'sitec.legislateiro');
        

        $this->setProviders();
        // $this->routes();



        // Register Migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->app->singleton(
            'legislateiro', function () {
                return new Legislateiro();
            }
        );
        
        /*
        |--------------------------------------------------------------------------
        | Register the Utilities
        |--------------------------------------------------------------------------
        */
        /**
         * Singleton Legislateiro;
         */
        $this->app->singleton(
            LegislateiroService::class, function ($app) {
                Log::channel('sitec-legislateiro')->info('Singleton Legislateiro;');
                return new LegislateiroService(\Illuminate\Support\Facades\Config::get('sitec.legislateiro'));
            }
        );

        // Register commands
        $this->registerCommandFolders(
            [
            base_path('vendor/sierratecnologia/legislateiro/src/Console/Commands') => '\Legislateiro\Console\Commands',
            ]
        );

        // /**
        //  * Helpers
        //  */
        // Aqui noa funciona
        // if (!function_exists('legislateiro_asset')) {
        //     function legislateiro_asset($path, $secure = null)
        //     {
        //         return route('rica.legislateiro.assets').'?path='.urlencode($path);
        //     }
        // }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'legislateiro',
        ];
    }

    /**
     * Register configs, migrations, etc
     *
     * @return void
     */
    public function registerDirectories()
    {
        // Publish config files
        $this->publishes(
            [
            // Paths
            $this->getPublishesPath('config/sitec') => config_path('sitec'),
            ], ['config',  'sitec', 'sitec-config']
        );

        // // Publish legislateiro css and js to public directory
        // $this->publishes([
        //     $this->getDistPath('legislateiro') => public_path('assets/legislateiro')
        // ], ['public',  'sitec', 'sitec-public']);

        $this->loadViews();
        $this->loadTranslations();

    }

    private function loadViews()
    {
        // View namespace
        $viewsPath = $this->getResourcesPath('views');
        $this->loadViewsFrom($viewsPath, 'legislateiro');
        $this->publishes(
            [
            $viewsPath => base_path('resources/views/vendor/legislateiro'),
            ], ['views',  'sitec', 'sitec-views']
        );

    }
    
    private function loadTranslations()
    {
        // Publish lanaguage files
        $this->publishes(
            [
            $this->getResourcesPath('lang') => resource_path('lang/vendor/legislateiro')
            ], ['lang',  'sitec', 'sitec-lang', 'translations']
        );

        // Load translations
        $this->loadTranslationsFrom($this->getResourcesPath('lang'), 'legislateiro');
    }


    /**
     * 
     */
    private function loadLogger()
    {
        Config::set(
            'logging.channels.sitec-legislateiro', [
            'driver' => 'single',
            'path' => storage_path('logs/sitec-legislateiro.log'),
            'level' => env('APP_LOG_LEVEL', 'debug'),
            ]
        );
    }

}
