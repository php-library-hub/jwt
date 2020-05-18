<?php
/**
 * Created by PhpStorm.
 * Filename: JwtGenerateSecretCommand.php
 * User: liumingliang
 * Email: liumingliang@qie.tv
 * Date: 2020/5/18
 * Time: 2:40 下午
 */

namespace JwtLibrary\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class JwtGenerateSecretCommand extends Command
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'jwt:secret
        {--o|original : Show original key}
        {--s|show : Display the key instead of modifying files.}
        {--always-no : Skip generating key if it already exists.}
        {--f|force : Skip confirmation when overwriting an existing key.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set the jwt secret key used to sign the tokens';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        if ($this->option('original')) {
            $this->showOriginal();
            return;
        }

        $key = Str::random(64);

        if ($this->option('show')) {
            $this->comment($key);
            return;
        }

        if (file_exists($path = $this->envPath()) === false) {
            $this->displayKey($key);
            return;
        }

        if (Str::contains(file_get_contents($path), 'JWT_SECRET') === false) {
            file_put_contents($path, PHP_EOL . "JWT_SECRET=$key" . PHP_EOL, FILE_APPEND);
        } else {
            if ($this->option('always-no')) {
                $this->comment('Secret key already exists. Skipping...');
                return;
            }

            if ($this->isConfirmed() === false) {
                $this->comment('Phew... No changes were made to your secret key.');
                return;
            }

            file_put_contents($path, str_replace(
                'JWT_SECRET=' . $this->laravel['config']['jwt.secret'],
                'JWT_SECRET=' . $key, file_get_contents($path)
            ));
        }

        $this->displayKey($key);
    }

    /**
     * Show original key.
     *
     * @return void
     */
    protected function showOriginal(): void
    {
        $this->comment($this->laravel['config']->get('jwt.secret'));
    }

    /**
     * Display the key.
     *
     * @param string $key
     *
     * @return void
     */
    protected function displayKey(string $key): void
    {
        $this->laravel['config']['jwt.secret'] = $key;

        $this->info("jwt secret [$key] set successfully.");
    }

    /**
     * Check if the modification is confirmed.
     *
     * @return bool
     */
    protected function isConfirmed(): bool
    {
        return $this->option('force') ? true : $this->confirm(
            'This will invalidate all existing tokens. Are you sure you want to override the secret key?'
        );
    }

    /**
     * Get the .env file path.
     *
     * @return string
     */
    protected function envPath(): string
    {
        if (method_exists($this->laravel, 'environmentFilePath')) {
            return $this->laravel->environmentFilePath();
        }

        return $this->laravel->basePath('.env');
    }

}