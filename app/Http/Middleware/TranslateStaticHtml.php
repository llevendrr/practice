<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TranslateStaticHtml
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (app()->getLocale() !== 'en') {
            return $response;
        }

        $contentType = (string) $response->headers->get('Content-Type');
        if (! str_contains($contentType, 'text/html')) {
            return $response;
        }

        $content = $response->getContent();
        if (! is_string($content) || $content === '') {
            return $response;
        }

        $dictionary = $this->dictionary();
        if ($dictionary === []) {
            return $response;
        }

        $response->setContent(strtr($content, $dictionary));

        return $response;
    }

    /**
     * @return array<string, string>
     */
    private function dictionary(): array
    {
        static $dictionary = null;

        if (is_array($dictionary)) {
            return $dictionary;
        }

        $path = lang_path('en.json');
        if (! is_file($path)) {
            return $dictionary = [];
        }

        $raw = json_decode((string) file_get_contents($path), true);
        if (! is_array($raw)) {
            return $dictionary = [];
        }

        $pairs = [];
        foreach ($raw as $uk => $en) {
            if (! is_string($uk) || ! is_string($en)) {
                continue;
            }

            if ($uk === '' || $uk === $en) {
                continue;
            }

            $pairs[$uk] = $en;
        }

        uksort($pairs, fn (string $a, string $b): int => mb_strlen($b) <=> mb_strlen($a));

        return $dictionary = $pairs;
    }
}
