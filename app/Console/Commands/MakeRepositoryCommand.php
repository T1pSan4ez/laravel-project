<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeRepositoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a repository with interface';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');

        $interfacePath = app_path("Repositories/{$name}Interface.php");
        if (!File::exists($interfacePath)) {
            File::put($interfacePath, "<?php\n\nnamespace App\Repositories;\n\ninterface {$name}Interface\n{\n    // Define your methods here\n}");
            $this->info("Interface {$name}Interface created successfully.");
        }

        $repositoryPath = app_path("Repositories/{$name}.php");
        if (!File::exists($repositoryPath)) {
            File::put($repositoryPath, "<?php\n\nnamespace App\Repositories;\n\nuse App\Repositories\\{$name}Interface;\n\nclass {$name} implements {$name}Interface\n{\n    // Implement your methods here\n}");
            $this->info("Repository {$name} created successfully.");
        }
    }
}
