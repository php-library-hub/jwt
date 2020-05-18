<?php
/**
 * Created by PhpStorm.
 * Filename: JwtServiceProvider.php
 * User: liumingliang
 * Email: liumingliang@qie.tv
 * Date: 2020/5/16
 * Time: 11:05 下午
 */

namespace JwtLibrary\Providers;

use Illuminate\Console\Application as Artisan;
use Illuminate\Support\ServiceProvider;


class JwtServiceProvider extends ServiceProvider
{

    /**
     * The middleware aliases.
     *
     * @var array
     */
    protected $middlewareAliases = [

    ];

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerAliases();

        $this->commands('tymon.jwt.secret');
    }

    /**
     * Boot the service provider.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Bind some aliases.
     *
     * @return void
     */
    protected function registerAliases()
    {

    }

    /**
     * Register the package's custom Artisan commands.
     *
     * @param  array|mixed  $commands
     * @return void
     */
    public function commands($commands)
    {
        $commands = is_array($commands) ? $commands : func_get_args();

        Artisan::starting(function ($artisan) use ($commands) {
            $artisan->resolveCommands($commands);
        });
    }
}