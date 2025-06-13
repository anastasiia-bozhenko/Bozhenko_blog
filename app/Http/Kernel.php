namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
/**
* The application's global HTTP middleware stack.
*
* These middleware are run during every request to your application.
*
* @var array<int, class-string>
*/
protected $middleware = [
// \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
\Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
\App\Http\Middleware\TrimStrings::class,
\Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
];

/**
* The application's route middleware groups.
*
* @var array<string, array<int, class-string>>
*/
protected $middlewareGroups = [
'web' => [
\App\Http\Middleware\EncryptCookies::class,
\Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
\Illuminate\Session\Middleware\StartSession::class,
\Illuminate\View\Middleware\ShareErrorsFromSession::class,
\App\Http\Middleware\VerifyCsrfToken::class,
\Illuminate\Routing\Middleware\SubstituteBindings::class,
],

'api' => [
// \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
// 'throttle:api',
\Illuminate\Routing\Middleware\SubstituteBindings::class,
 \Fruitcake\Cors\HandleCors::class, // Цей рядок закоментований або видалений, якщо ми використовуємо свій CorsMiddleware

],
];

/**
* The application's route middleware.
*
* These middleware may be assigned to groups or individual routes.
*
* @var array<string, class-string>
*/
protected $routeMiddleware = [
'auth' => \App\Http\Middleware\Authenticate::class,
'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
'can' => \Illuminate\Auth\Middleware\Authorize::class,
'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
'password.confirm' => \App\Http\Middleware\RequirePasswordConfirmation::class,
'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
];

/**
* The priority-sorted list of middleware.
*
* This determines the order in which the middleware are executed when
* the request passes through this application's HTTP kernel.
*
* @var array<int, class-string>
*/
protected $middlewarePriority = [
\Illuminate\Session\Middleware\StartSession::class,
\Illuminate\View\Middleware\ShareErrorsFromSession::class,
\Illuminate\Routing\Middleware\ThrottleRequests::class,
\Illuminate\Session\Middleware\AuthenticateSession::class,
\Illuminate\Routing\Middleware\SubstituteBindings::class,
\Illuminate\Auth\Middleware\Authorize::class,
];
}
