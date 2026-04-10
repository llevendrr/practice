<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class LocaleController extends Controller
{
    public function __invoke(Request $request, string $locale): RedirectResponse
    {
        $supported = config('app.supported_locales', ['uk', 'en']);

        abort_unless(in_array($locale, $supported, true), 404);

        app()->setLocale($locale);
        $request->session()->put('locale', $locale);

        Cookie::queue(cookie(
            name: 'locale',
            value: $locale,
            minutes: 60 * 24 * 365,
            path: '/',
        ));

        return redirect()->back();
    }
}
