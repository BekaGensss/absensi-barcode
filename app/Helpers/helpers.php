<?php

if (! function_exists('is_active')) {
    function is_active($route) {
        return Route::currentRouteName() == $route ? 'active' : '';
    }
}

if (! function_exists('is_parent_active')) {
    function is_parent_active($routes) {
        $currentRoute = Route::currentRouteName();
        foreach ($routes as $route) {
            if (str_starts_with($currentRoute, $route)) {
                return 'active';
            }
        }
        return '';
    }
}