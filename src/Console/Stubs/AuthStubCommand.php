<?php

namespace SevenLab\LaravelDefaults\Console\Stubs;


use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Symfony\Component\Process\Process;

class AuthStubCommand extends Command
{
    protected $signature = 'lab:stubs:auth';
    protected $description = 'Add auth stubs to the application';
    protected $baseStubPath = __DIR__ . '/../../../stubs/';

    public function handle(): int
    {
        $publishLoginStubs = $this->askWithCompletion('Do you want to add login stubs', ['yes', 'no'], 'yes');
        $publishRegisterStubs = $this->askWithCompletion('Do you want to add register stubs', ['yes', 'no'], 'yes');
        $publishPasswordResetStubs = $this->askWithCompletion('Do you want to add password reset stubs', ['yes', 'no'], 'yes');

        $this->info('Publishing user resource');
        $this->copyFromStubs('App/Http/Resources/Api/User/UserResource.php');

        $this->installSanctum();

        if ($publishLoginStubs) {
            $this->publishLoginStubs();
        }

        if ($publishRegisterStubs) {
            $this->publishRegisterStubs();
        }

        if ($publishPasswordResetStubs) {
            $this->publishPasswordResetStubs();
        }

        $this->info('Auth stubs installed. You may need to run the migrations to finish the installation');

        return Command::SUCCESS;
    }

    private function copyFromStubs($source): void
    {
        $destinationPath = base_path($source);

        $fileSystem = new Filesystem();

        $this->prepareDestinationPath($destinationPath, $fileSystem);

        $fileSystem->copy(
            $this->baseStubPath . $source,
            $destinationPath
        );
    }

    private function addRoute($method, $uri, $action, $name): void
    {
        $routeNameNeedle = sprintf("name('%s')", $name);

        if (!Str::contains(file_get_contents(base_path('routes/api.php')), $routeNameNeedle)) {
            $route = <<<'EOF'

            Route::%s('%s', %s)
                ->name('%s');
            EOF;

            $route = sprintf($route, $method, $uri, $action, $name);

            (new Filesystem)
                ->append(base_path('routes/api.php'), $route);
        }
    }

    private function prepareDestinationPath(string $destinationPath, Filesystem $fileSystem): void
    {
        $directories = array_filter(
            explode('/', $destinationPath)
        );
        array_pop($directories);

        $currentPath = null;

        foreach ($directories as $directory) {
            $currentPath .= '/' . $directory;
            $fileSystem->ensureDirectoryExists(
                $currentPath
            );
        }
    }

    private function publishLoginStubs(): void
    {
        $this->info('Publishing login stubs');

        $this->copyFromStubs('App/Http/Controllers/Api/Auth/LoginController.php');
        $this->copyFromStubs('App/Http/Requests/Api/Auth/LoginRequest.php');

        $this->addRoute('get', '/user', 'App\Http\Controllers\Api\Auth\LoginController::class', 'login');
    }

    private function publishRegisterStubs(): void
    {
        $this->info('Publishing register stubs');

        $this->copyFromStubs('App/Http/Controllers/Api/Auth/RegisterController.php');
        $this->copyFromStubs('App/Http/Requests/Api/Auth/RegisterRequest.php');

        $this->addRoute('get', '/register', 'App\Http\Controllers\Api\Auth\RegisterController::class', 'register');
    }

    private function publishPasswordResetStubs(): void
    {
        $this->info('Publishing password reset stubs');

        $this->copyFromStubs('App/Http/Controllers/Api/Auth/PasswordResetController.php');
        $this->copyFromStubs('App/Http/Requests/Api/Auth/PasswordForgetRequest.php');
        $this->copyFromStubs('App/Http/Requests/Api/Auth/PasswordResetRequest.php');
        $this->copyFromStubs('App/Providers/AuthServiceProvider.php');

        $this->addRoute('get', '/password/reset', "[App\Http\Controllers\Api\Auth\PasswordResetController::class, 'forget']", 'password.forget');
        $this->addRoute('post', '/password/reset', "[App\Http\Controllers\Api\Auth\PasswordResetController::class, 'reset']", 'password.reset');
    }

    private function installSanctum(): void
    {
        $this->info('Installing laravel sanctum');

        $this->requireComposerPackage('laravel/sanctum');

        $this->callSilent('vendor:publish', [
            '--provider' => 'Laravel\Sanctum\SanctumServiceProvider',
            '--tag' => 'config',
        ]);
    }

    private function requireComposerPackage(string $package): void
    {
        $command = [
            'composer',
            'require',
            $package,
        ];

        (new Process($command, base_path('vendor/bin'), ['COMPOSER_MEMORY_LIMIT' => '-1']))
            ->setTimeout(null)
            ->run(function ($type, $output) {
                $this->output->write($output);
            });
    }
}
