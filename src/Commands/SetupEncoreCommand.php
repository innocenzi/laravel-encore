<?php

namespace Innocenzi\LaravelEncore\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;

class SetupEncoreCommand extends Command
{
    protected $signature   = 'encore:setup';
    protected $description = 'Setup Webpack Encore.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->updatePackage();
        $this->updateWebpackConfig();
        $this->updateGitIgnore();

        $this->info('Webpack Encore has been installed.');
        $this->output->writeln('- Make sure to run <fg=yellow>npm install</> and <fg=yellow>npm run dev</>.');
        $this->output->writeln('- If you have any CSS, you can include it in your Javascript.');
    }

    protected function updatePackage(): void
    {
        $packages = \json_decode(\file_get_contents(\base_path('package.json')), true);

        $packages['devDependencies'] = [
            '@symfony/webpack-encore' => '^0.30',
        ] + Arr::except($packages['devDependencies'], [
            'cross-env',
            'laravel-mix',
        ]);
        $packages['scripts'] = [
            'dev-server' => 'encore dev-server',
            'dev'        => 'encore dev',
            'watch'      => 'encore dev --watch',
            'build'      => 'encore production --progress',
        ];

        \file_put_contents(
            \base_path('package.json'),
            \json_encode($packages, \JSON_UNESCAPED_SLASHES | \JSON_PRETTY_PRINT) . \PHP_EOL
        );
    }

    protected function updateWebpackConfig(): void
    {
        File::delete(\base_path('webpack.mix.js'));

        if (File::exists($path = \base_path('webpack.config.js'))) {
            if ('Yes' !== $this->choice('A webpack.config.js already exists. Override?', ['No', 'Yes'])) {
                return;
            }
        }

        File::copy(__DIR__ . '/stubs/webpack.config.js', $path);
    }

    protected function updateGitIgnore(): void
    {
        $gitignore = \file_get_contents(\base_path('.gitignore'));
        $gitignore = '/public/build' . \PHP_EOL . $gitignore;
        \file_put_contents(\base_path('.gitignore'), $gitignore);
    }
}
