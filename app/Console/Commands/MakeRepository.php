<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeRepository extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new Repositories and Interfaces to models';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $modelName = $this->argument('model');

            if (! $this->createFileByTypes($modelName)) {
                $this->error('Error creating files');

                return Command::FAILURE;
            }
            // Create model with all related files
            if (! $this->createModel($modelName)) {
                return Command::FAILURE;
            }

            // Register in service provider
            if (! $this->addModelToRepositoryServiceProvider($modelName)) {
                return Command::FAILURE;
            }

            $this->info('All files were created successfully!');

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error("Error creating files: {$e->getMessage()}");

            return Command::FAILURE;
        }

    }

    public function createModel($modelName)
    {
        Artisan::call('make:model '.$modelName.' -a');

        return true;
    }

    public function createFileByTypes($modelName)
    {
        $types = ['Repository', 'Interface'];
        foreach ($types as $type) {
            if (! $this->createFileByType($modelName, $type)) {
                return false;
            }
        }

        return true;
    }

    public function createFileByType($modelName, $type)
    {
        $name = $modelName.$type;
        $pluralModelName = Str::pluralStudly($type);
        $path = app_path($pluralModelName.'/'.$name.'.php');
        if (file_exists($path)) {
            $this->info($type.' already exists');

            return false;
        }
        $stubRepositoryPath = base_path('stubs/'.strtolower($type).'.stub');
        $stubContent = File::get($stubRepositoryPath);
        $stubContent = str_replace('{{ model }}', $modelName, $stubContent);
        File::put($path, $stubContent);

        return true;
    }

    public function addModelToRepositoryServiceProvider($modelName)
    {
        try {
            $configPath = app_path('Providers/RepositoryServiceProvider.php');

            if (! File::exists($configPath)) {
                throw new \Exception('Repository Service Provider file not found.');
            }
            $content = File::get($configPath);
            $pattern = '/\$models\s*=\s*\[/';
            if (! preg_match($pattern, $content, $matches, PREG_OFFSET_CAPTURE)) {
                throw new \Exception('Models array not found in service provider.');
            }
            $startPosition = $matches[0][1] + strlen($matches[0][0]);
            $endPattern = '/];/';
            if (! preg_match($endPattern, $content, $endMatches, PREG_OFFSET_CAPTURE, $startPosition)) {
                throw new \Exception("Could not find the end of the 'models' array.");
            }
            $endPosition = $endMatches[0][1];
            $arrayContent = trim(substr($content, $startPosition, $endPosition - $startPosition));

            if (strpos($arrayContent, "'".$modelName."'") !== false) {
                $this->warn("Model '{$modelName}' already exists in configuration.");

                return Command::SUCCESS;
            }

            $newArrayContent = rtrim($arrayContent, ',').",\n            '".$modelName."'";
            $newContent = substr_replace(
                $content,
                "[\n            ".$newArrayContent.",\n        ]",
                $startPosition - 2,
                $endPosition - $startPosition + 3
            );

            File::put($configPath, $newContent);
            $this->info("Model '{$modelName}' successfully added to the configuration.");

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error($e->getMessage());

            return Command::FAILURE;
        }
    }
}
