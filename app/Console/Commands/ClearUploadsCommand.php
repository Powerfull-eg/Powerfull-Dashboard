<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ClearUploadsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'uploads:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clears all uploads from the uploads directory';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $files = File::allFiles(public_path('uploads'));

        if (empty($files)) {
            $this->info('Uploads directory is empty!');

            return;
        }

        $this->info('Clearing uploads directory...');

        foreach ($files as $file) {
            $this->info('Deleting ' . $file->getFilename());

            File::delete($file->getPathname());
        }

        $this->info('Uploads directory cleared!');
    }
}
