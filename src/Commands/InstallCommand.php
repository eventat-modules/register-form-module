<?php

namespace Eventat\ModuleName\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Eventat\ModuleGenerator\Generator;
use Illuminate\Support\Facades\Process;

use function Laravel\Prompts\multiselect;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module-name:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install module name';

    /**
     * The list of the starter kit's features.
     */
    protected array $features = [];

    /**
     * Execute the console command.
     *
     * @throws \Exception
     */
    public function handle()
    {
        $this->info('⌛ Installing module name ...');

        $this->newLine();

        $this->askForRequestedFeatures();

        if (in_array('feature1', $this->features)) {
            $this->installFeatureOne();
        }

        if (in_array('feature2', $this->features)) {
            $this->installFeatureTwo();
        }

        $this->updateComposer();

        $this->newLine();

        $this->info('✅ All Done');
    }

    protected function installFeatureOne(): void
    {
        $this->line('⌛ Installing feature 1 ...');

        $this->newLine();

        $this->generator()
            ->publish(__DIR__.'/../../stubs/feature-1')
            ->registerServiceProvider('App\Providers\FeatureOneServiceProvider');

        // Steps to install feature ...

        $this->info('✅ Feature one has been installed.');
    }

    protected function installFeatureTwo(): void
    {
        $this->line('⌛ Installing feature 2 ...');

        $this->newLine();

        $this->generator()
            ->publish(__DIR__.'/../../stubs/feature-2')
            ->registerServiceProvider('App\Providers\FeatureTwoServiceProvider');

        // Steps to install feature ...

        $this->info('✅ Feature two has been installed.');
    }

    /**
     * Ask about features you want to install.
     */
    protected function askForRequestedFeatures(): void
    {
        $this->features = multiselect(
            label: 'Select the features that you want to install.',
            options: [
                'feature1' => 'Feature One',
                'feature2' => 'Feature Two',
            ],
            default: ['feature1', 'feature2'],
        );
    }

    /**
     * Update composer packages.
     */
    protected function updateComposer(): void
    {
        Process::run('composer update', function ($type, $output) {
            $this->line($output);
        });
    }

    /**
     * Get the module generator instance.
     */
    protected function generator(): Generator
    {
        return App::make(Generator::class);
    }
}
