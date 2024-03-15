<?php
namespace Core;

use App\Controllers\Authorization;

/**
 * Class Controller
 * @package Core
 */
abstract class Controller
{
    /**
     * Parameters from the matched route
     * @var array
     */
    protected $route_params = [];

    /**
     * Class constructor
     *
     * @param array $route_params  Parameters from the route
     *
     * @return void
     */
    public function __construct(array $route_params)
    {
        $this->route_params = $route_params;
    }

    /**
     * Magic method called when a non-existent or inaccessible method is
     * called on an object of this class. Used to execute before and after
     * filter methods on action methods. Action methods need to be named
     * with an "Action" suffix, e.g. indexAction, showAction etc.
     *
     * @param string $name  Method name
     * @param array $args Arguments passed to the method
     *
     * @return void
     */
    public function __call(string $name, array $args)
    {
        $method = $name . 'Action';

        if (method_exists($this, $method)) {
            if ($this->before() !== false) {
                call_user_func_array([$this, $method], $args);
            }
        } else {
            throw new \Exception("Method $method not found in controller " . get_class($this));
        }
    }

    /**
     * Before filter - called before an action method.
     *
     * @return void
     */
    protected function before()
    {
        //Проверка авторизации
        $check_auth = Authorization::checkAuth();

        if(!$check_auth && $this->route_params['controller'] != 'Authorization' && $this->route_params['action'] != 'auth') {
            header('Location: /login');
            exit;
        }

        if($check_auth && $this->route_params['controller'] == 'Authorization' && $this->route_params['action'] == 'auth') {
            header('Location: /');
            exit;
        }
    }
}