<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * This File was made by [AQuadic Software Solutions](AQuadic.com).
 * if you are happy with this file and used it,
 * send us thanks letter on [hello@aquadic.com](mailto:hello@aquadic.com)
 *
 * File Path: app/Http/Middleware/Localization.php
 */
class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response|RedirectResponse) $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $this->getLocale($request, null);

        return $next($request);
    }

    protected function getLocale(Request $request, ?string $defaultLocale): string
    {
        if (!$defaultLocale) {
            $defaultLocale = $this->parseHttpLocale($request) ?? config('app.locale');
        }

        if (!in_array($defaultLocale, array_keys(config('app.available_locales')))) {
            $defaultLocale = array_keys(config('app.available_locales'))[0];
        }

        app()->setLocale($defaultLocale);

        return $defaultLocale;
    }

    private function parseHttpLocale(Request $request): string
    {
        $list = explode(',', $request->header('Accept-Language', config('app.locale')));

        $locales = collect($list)
            ->map(function ($locale) {
                $parts = explode(';', $locale);

                $mapping['locale'] = trim($parts[0]);

                if (isset($parts[1])) {
                    $factorParts = explode('=', $parts[1]);

                    $mapping['factor'] = $factorParts[1];
                } else {
                    $mapping['factor'] = 1;
                }

                return $mapping;
            })
            ->sortByDesc(function ($locale) {
                return $locale['factor'];
            });

        return explode($locales->first()['locale'], '-')[0];
    }
}
