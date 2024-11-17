<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        config(['app.locale' => 'id']);
	    Carbon::setLocale('id');
        Paginator::useBootstrapFive();
        Blade::directive('currency', function ( $expression ) { return "Rp <?php echo number_format($expression,0,',','.'); ?>"; });
        Blade::directive('number', function ( $expression ) { return "<?php echo number_format($expression,0,',','.'); ?>"; });
    }
}
