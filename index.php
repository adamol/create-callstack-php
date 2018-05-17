<?php

class Request
{
    public $first;
    public $second;
    public $third;
}

$first = function(Request $request, $next) {
    $request->first = 'first';

    var_dump('first called');

    return $next($request);
};

$second = function(Request $request, $next) {
    $request->second = 'second';

    var_dump('second called');

    return $next($request);
};

$third = function(Request $request, $next) {
    $request->third = 'third';

    var_dump('third called');

    return $next($request);
};

$initial = function($request) {
    var_dump(json_encode($request));
};

$pipes = array_reverse([$first, $second, $third]);

$callback = function($stack, $pipe) {
    return function ($request) use ($stack, $pipe) {
        return call_user_func($pipe, $request, $stack);
    };
};

$callStack = array_reduce($pipes, $callback, $initial);

call_user_func($callStack, new Request);
