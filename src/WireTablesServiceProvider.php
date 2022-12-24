<?php

namespace Helvetiapps\WireTables;

use Helvetiapps\WireTables\Http\Livewire\WireTable;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class WireTablesServiceProvider extends ServiceProvider
{
  public function register()
  {
    $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'wiretables');
  }

  public function boot()
  {
    $this->loadViewsFrom(__DIR__.'/../resources/views', 'wiretables');

    Livewire::component('table', WireTable::class);

    if ($this->app->runningInConsole())
    {
      $this->publishes([
        __DIR__.'/../config/config.php' => config_path('wiretables.php'),
      ], 'config');
    }
  }
}
