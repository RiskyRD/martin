<?php

namespace Core\HttpFoundation;

use Core\HttpFoundation\Request;

interface Middleware
{
    public function handle(Request $request, callable $next);
}
