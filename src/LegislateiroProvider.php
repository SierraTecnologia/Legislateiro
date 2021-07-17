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

    public $packageName = 'legislateiro';
    const pathVendor = 'sierratecnologia/legislateiro';

    public static $aliasProviders = [
        'Legislateiro' => \Legislateiro\Facades\Legislateiro::class,
    ];

    public static $providers = [
        // \Transmissor\TransmissorProvider::class,
        // \Population\PopulationProvider::class,
        // \Telefonica\TelefonicaProvider::class,
        // \Bancario\BancarioProvider::class,

        
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
                'text'        => 'Contratos',
                'icon'        => 'fas fa-fw fa-search',
                'icon_color'  => 'blue',
                'label_color' => 'success',
                'level'       => 3, // 0 (Public), 1, 2 (Admin) , 3 (Root)
                // 'access' => \Porteiro\Models\Role::$ADMIN
            ],
            'Contratos' => [
                [
                    'text'        => 'Projetos',
                    'route'       => 'admin.legislateiro.parteTypes.index',
                    'icon'        => 'fas fa-fw fa-ship',
                    'icon_color'  => 'blue',
                    'label_color' => 'success',
                    'level'       => 3, // 0 (Public), 1, 2 (Admin) , 3 (Root)
                    // 'access' => \Porteiro\Models\Role::$ADMIN
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
        $this->app->booted(function () {
            $this->routes();
        });

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
         * Transmissor; Routes
         */
        $this->loadRoutesForRiCa(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'routes');
    }


    /**
     * Register the services.
     */
    public function register()
    {
        $this->mergeConfigFrom($this->getPublishesPath('config'.DIRECTORY_SEPARATOR.'legislateiro.php'), 'legislateiro');
        // Publish config files

        if ($this->app->runningInConsole()) {
            $this->publishes(
                [
                __DIR__ . '/../config/legislateiro.php' => config_path('legislateiro.php'),
                ],
                'config'
            );

            $this->publishes(
                [
                __DIR__ . '/../resources/views' => base_path('resources'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'legislateiro'),
                ],
                'views'
            );
        }

        $this->setProviders();
        // $this->routes();



        // Register Migrations
        $this->loadMigrationsFrom(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'database'.DIRECTORY_SEPARATOR.'migrations');

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
            $this->getPublishesPath('config'.DIRECTORY_SEPARATOR.'sitec') => config_path('sitec'),
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
            $viewsPath => base_path('resources'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'legislateiro'),
            ], ['views',  'sitec', 'sitec-views']
        );

    }
    
    private function loadTranslations()
    {
        // Publish lanaguage files
        $this->publishes(
            [
            $this->getResourcesPath('lang') => resource_path('lang'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'legislateiro')
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
            'path' => storage_path('logs'.DIRECTORY_SEPARATOR.'sitec-legislateiro.log'),
            'level' => env('APP_LOG_LEVEL', 'debug'),
            ]
        );
    }

}
