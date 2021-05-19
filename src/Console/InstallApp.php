<?php

namespace Laracle\ShopBox\Console;

use Illuminate\Console\Command;

class InstallApp extends Command
{
    protected $signature = 'shopbox:install';

    protected $description = 'Installs ShopBox';

    public function handle()
    {
        $this->info('Installing ShopBox...');

        // Add migrations

        /*
        $this->call('vendor:publish',[
          '--tag' => "shopbox.app",
          'force'
        ]);

        $this->info('Created admin UI');

          */

        $this->info('ShopBox Installed');
    }
}
