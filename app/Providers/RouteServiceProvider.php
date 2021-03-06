<?php

namespace App\Providers;

use App\Models\Busi\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use App\Services\LogSvr;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //
        Route::pattern('id', '[0-9]+');
        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
    	$request = Request::instance();
	    $path = $request->path();
	    //LogSvr::routeSvr()->info($path);
		if(preg_match('/^api/', $path)) {
			$this->mapApiRoutes();
		}
	    if(preg_match('/^\//', $path)) {
		    $this->mapWebRoutes();
	    }
	    if(preg_match('/^admin/', $path)) {
		    $this->mapAdminRoutes();
	    }
	    if(preg_match('/^customer/', $path)) {
		    $this->mapCustomerRoutes();
	    }
        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::group([
            'middleware' => 'web',
            'namespace' => $this->namespace,
        ], function ($router) {
            require base_path('routes/web.php');
        });
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::group([
            'middleware' => 'api',
            'namespace' => $this->namespace . '\Api',
            'prefix' => 'api',
        ], function ($router) {
            require base_path('routes/api.php');
            $this->load_routes(base_path('routes/api'));
        });
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapAdminRoutes()
    {
        Route::group([
            'middleware' => 'admin',
            'namespace' => $this->namespace . '\Admin',
            'prefix' => 'admin',
        ], function ($router) {
            //require base_path('routes/admin.php');
            $this->load_routes(base_path('routes/admin'));
        });
    }

	/**
	 * Define the "api" routes for the application.
	 *
	 * These routes are typically stateless.
	 *
	 * @return void
	 */
	protected function mapCustomerRoutes()
	{
		Route::group([
			'middleware' => 'customer',
			'namespace' => $this->namespace . '\Customer',
			'prefix' => 'customer',
		], function ($router) {
			//require base_path('routes/admin.php');
			$this->load_routes(base_path('routes/customer'));
		});
	}


    protected function load_routes($dir)
    {
        foreach (glob($dir . '/*') as $filename) {
            if (is_dir($filename)) {
                load_routes($filename);
            } elseif (is_file($filename)) {
                require $filename;
            }
        }
    }
}
