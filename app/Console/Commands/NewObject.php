<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class NewObject extends Command
{
    protected $signature = 'make:newobject {folderName}';
    protected $description = 'Creates a folder with 4 JS files';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $folderName = $this->argument('folderName');
        $path = resource_path("js/Pages/{$folderName}");

        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);

            $files = ['Index.vue', 'Create.vue', 'Show.vue', 'Edit.vue'];

            foreach ($files as $file) {
                File::put("{$path}/{$file}", "// JavaScript content for {$file}\n");
                $this->info("Created: {$path}/{$file}");
            }
        } else {
            $this->error("The folder '{$folderName}' already exists!");
        }

        return 0;
    }
}
