<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

// Polyfill transparente: Caso a extensão nativa php-mbstring não esteja habilitada no ambiente CLI,
// definimos mb_strimwidth para evitar que o renderizador de terminal (Termwind) falhe ao executar o Artisan.
if (!function_exists('mb_strimwidth')) {
    function mb_strimwidth(string $string, int $start, int $width, string $trim_marker = '', ?string $encoding = null): string
    {
        $strWidth = mb_strwidth($string);
        if ($strWidth <= $width) {
            return $string;
        }
        $markerWidth = mb_strwidth($trim_marker);
        $targetWidth = max(0, $width - $markerWidth);
        $result = '';
        for ($i = 0; $i < mb_strlen($string); $i++) {
            $char = mb_substr($string, $i, 1);
            if (mb_strwidth($result . $char) > $targetWidth) {
                break;
            }
            $result .= $char;
        }
        return $result . $trim_marker;
    }
}

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
