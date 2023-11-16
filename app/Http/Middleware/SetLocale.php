<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $lang = $request->route()->parameter('lang', 'fa');

        if($lang != 'fa' && $lang != 'en')
            return Redirect::route('index', ['lang' => 'fa']);

        if($lang == 'fa' && Controller::$LANG_MODE == 'just_en')
            return Redirect::route('index', ['lang' => 'en']);
        
        else if($lang == 'en' && Controller::$LANG_MODE == 'just_fa')
            return Redirect::route('index', ['lang' => 'fa']);

        App::setLocale($lang);
        return $next($request);
    }
}
