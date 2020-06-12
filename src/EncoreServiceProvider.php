<?php

namespace Innocenzi\LaravelEncore;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Innocenzi\LaravelEncore\Commands\SetupEncoreCommand;

class EncoreServiceProvider extends ServiceProvider
{
    const STYLES_BLADE_DIRECTIVE  = 'styles';
    const SCRIPTS_BLADE_DIRECTIVE = 'scripts';

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('encore.php'),
            ], 'config');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/laravel-encore'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/laravel-encore'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/laravel-encore'),
            ], 'lang');*/

            // Registering package commands.
            $this->commands([
                SetupEncoreCommand::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'encore');

        // Register the main class to use with the facade
        $this->app->singleton(Facade\Encore::class, fn () => new Encore());

        // Register the blade directives
        $this->registerBladeDirectives();
    }

    /**
     * Register the blade directives.
     */
    protected function registerBladeDirectives(): void
    {
        Blade::directive(self::STYLES_BLADE_DIRECTIVE, function (string $expression) {
            [$entryName] = explode(',', $expression);

            return sprintf(
                '<?php echo %s::getLinkTags(e(%s)); ?>',
                Facade\Encore::class,
                empty($entryName) ? '"app"' : $entryName,
            );
        });

        Blade::directive(self::SCRIPTS_BLADE_DIRECTIVE, function (string $expression) {
            [$entryName, $noDefer] = explode(',', $expression) + [null, 'false'];

            return sprintf(
                '<?php echo %s::getScriptTags(e(%s),e(%s)); ?>',
                Facade\Encore::class,
                empty($entryName) ? '"app"' : $entryName,
                trim($noDefer ?: 'false')
            );
        });
    }
}
