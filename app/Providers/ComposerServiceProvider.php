<?php

// app/Providers/ComposerServiceProvider.php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('*', function ($view) {
            $puntajeMaximo = DB::table('puntajes_maximos')
                ->where('clave', 'puntajeMaximo')
                ->value('valor');

                
            $view->with('puntajeMaximoGlobal', $puntajeMaximo);
        });
    }
}
