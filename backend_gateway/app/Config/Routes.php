<?php namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index',['filter' => 'apiFilter']);
$routes->options('/(:any)', 'Home::index',['filter' => 'apiFilter']);

/**
 * V1 API 說明書與路由設定
 */
$routes->group(
    'api/v1',
    [
        'namespace' => 'App\Controllers\V1',
        'filter' => 'apiFilter'
    ],
    function($routes){
        $routes->resource('openapi',[
            'controller' =>'OpenApi',
            'only' => ['index'],
        ]);
        //USER APIs
        $routes->post("user/login","User::login");
        $routes->put("user/refresh/(:segment)","User::refresh/$1");
        $routes->put("user","User::updateUser");
        $routes->resource("user",[
            'controller' => 'User',
            'only' => ['index','create','delete'],
        ]);

        //Without APIs
        $routes->resource("without",[
            'controller' =>"Without",
            'only' => ['index','show','create','update','delete'],
            'filter' => 'authFilter'
        ]);
        $routes->get('reference/(:segment)','Reference::show/$1',['filter' => 'apiFilter']);

        //Creation APIs
        $routes->post('creation/post','Creation::post',['filter' => 'apiFilter']);
        $routes->get('creation/(:segment)','Creation::show/$1',['filter' => 'apiFilter']);
        $routes->resource("creation",[
            'controller' =>"Creation",
            'only' => ['index','create','update','delete'],
            'filter' => 'authFilter'
        ]);
        $routes->get('tag','Tag::index',['filter' => 'apiFilter']);

        //Makeup APIs
        $routes->post('makeup/trial','Makeup::trial',['filter' => 'apiFilter']);
        $routes->resource("makeup",[
            'controller' =>"Makeup",
            'only' => ['index','show','create','update','delete'],
            'filter' => 'authFilter'
        ]);
    }
);

/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}

