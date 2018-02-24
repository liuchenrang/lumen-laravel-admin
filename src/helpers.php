<?php

if (!function_exists('admin_path')) {

    /**
     * Get admin path.
     *
     * @param string $path
     *
     * @return string
     */
    function admin_path($path = '')
    {
        return ucfirst(config('admin.directory')).($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
}

if (!function_exists('admin_url')) {
    /**
     * Get admin url.
     *
     * @param string $url
     *
     * @return string
     */
    function admin_url($url = '')
    {
        $prefix = trim(config('admin.prefix'), '/');

        return url($prefix ? "/$prefix" : '').'/'.trim($url, '/');
    }
}

if (!function_exists('admin_toastr')) {

    /**
     * Flash a toastr messaage bag to session.
     *
     * @param string $message
     * @param string $type
     * @param array  $options
     *
     * @return string
     */
    function admin_toastr($message = '', $type = 'success', $options = [])
    {
        $toastr = new \Illuminate\Support\MessageBag(get_defined_vars());

        \Illuminate\Support\Facades\Session::flash('toastr', $toastr);
    }
}

if (!function_exists('resource')) {

    /**
     * Flash a toastr messaage bag to session.
     *
     * @param string $message
     * @param string $type
     * @param array  $options
     *
     * @return string
     */
    function resource($router, $uri, $controller, $array = [])
    {
        $router->get($uri, $controller . '@index');

        $router->get($uri . '/create', $controller . '@create');

        $router->post($uri, $controller . '@store');

        $router->get($uri . '/{id}', $controller . '@show');

        $router->get($uri . '/{id}/edit', $controller . '@edit');

        $router->put($uri . '/{id}', $controller . '@update');

        $router->patch($uri . '/{id}', $controller . '@update');

        $router->delete($uri . '/{id}', $controller . '@destroy');

        return app();
    }
}
