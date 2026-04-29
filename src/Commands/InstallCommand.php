<?php

namespace Eventat\RegisterForm\Commands;

use AhmedAliraqi\CrudGenerator\Console\Commands\Modifier;
use Illuminate\Console\Command;
use LaravelModules\ModuleGenerator\Generator;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\text;

class InstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'register-form:install {name? : Class (Singular), e.g User, Place, Car}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install register form module';

    /**
     * Execute the console command.
     *
     * @throws \Exception
     */
    public function handle()
    {
        $this->info('⌛ Installing register form module ...');

        $name = $this->argument('name') ?? text(label: 'What is the CRUD name?', default: 'visitor');

        $viewPath = str($name)->plural()->kebab()->toString();

        $this->newLine();

        $crud = app(Generator::class)->crud($name);

        $translateToArabic = confirm(
            label: 'Do you want to translate CRUD to Arabic?',
            default: false
        );

        if ($translateToArabic) {
            $singular1 = text('Enter the Arabic singular name with “ال”, e.g. الزائر');
            $singular2 = text('Enter the Arabic singular name without “ال”, e.g. زائر');
            $plural1 = text('Enter the Arabic plural name with “ال”, e.g. الزوار');
            $plural2 = text('Enter the Arabic plural name without “ال”, e.g. زوار', 'زوار');
        }

        $crud->fromPath(__DIR__.'/../../stubs')
            ->toPath(base_path())
            ->appendToFile(
                file: resource_path('views/layouts/sidebar.blade.php'),
                content: "@include('dashboard.$viewPath.partials.actions.sidebar')",
                before: "@include('dashboard.settings.sidebar')",
            )
            ->publish();

        if ($translateToArabic) {
            $crud->fromPath(__DIR__."/../../stubs/lang/ar")
                ->toPath(base_path('lang/ar'))
                ->appendReplacements([
                    '__AR_SINGULAR1__' => $singular1,
                    '__AR_SINGULAR2__' => $singular2,
                    '__AR_PLURAL1__' => $plural1,
                    '__AR_PLURAL2__' => $plural2,
                ])
                ->publish();
        } else {
            $crud->fromPath(__DIR__."/../../stubs/lang/en")
                ->toPath(base_path('lang/ar'))
                ->publish();
        }

        app(Modifier::class)->permission($name);

        app(Modifier::class)->softDeletes($name);

        $this->info(
            sprintf(
                '✅ %s module has been installed successfully.',
                str($name)->plural()->snake(' ')->title()->toString()
            )
        );
    }
}
