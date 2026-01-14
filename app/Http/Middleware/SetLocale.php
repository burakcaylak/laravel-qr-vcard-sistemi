<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Carbon\Carbon;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $locale = 'tr'; // Default
        $settings = null;
        
        try {
            // Priority: authenticated user > settings > default (tr)
            if (auth()->check() && auth()->user()->language) {
                $locale = auth()->user()->language;
            } else {
                // Otherwise use settings language
                $settings = \App\Models\Settings::getSettings();
                if ($settings && $settings->language) {
                    $locale = $settings->language;
                }
            }
        } catch (\Exception $e) {
            // Settings table might not exist yet, use default
            \Log::warning('SetLocale middleware error', ['error' => $e->getMessage()]);
        }
        
        App::setLocale($locale);
        Carbon::setLocale($locale);
        
        return $next($request);
    }
}

