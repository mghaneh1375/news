<?php

namespace App\Http\Middleware;

use App\Http\Resources\NewsCategoryResource;
use App\Models\NewsCategory;
use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;

class NewsShareData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        App::setLocale($request->route()->parameter('lang', 'fa'));
        View::share(['newsCategories' => NewsCategoryResource::customCollection(NewsCategory::top()->get(), App::getLocale())->toArray($request)]);
        return $next($request);
    }
}
