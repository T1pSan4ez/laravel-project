<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeInterfaceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:interface {name : The name of the interface}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new interface';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $interfaceName = $name . 'Interface';

        $directory = app_path('Repositories');
        $filePath = $directory . '/' . $interfaceName . '.php';

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        if (File::exists($filePath)) {
            $this->error('Interface already exists!');
            return Command::FAILURE;
        }

        $interfaceContent = <<<EOT
<?php

namespace App\Repositories;

interface {$interfaceName}
{
    // Define your methods here
}
EOT;

        File::put($filePath, $interfaceContent);

        $this->info("Interface {$interfaceName} created successfully!");

        return Command::SUCCESS;
    }
}
