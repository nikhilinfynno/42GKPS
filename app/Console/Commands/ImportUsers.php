<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;

class ImportUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $file  = database_path("data/Users.xlsx");

        if (!file_exists($file) || !is_readable($file)) {
            $this->error('File does not exist or is not readable.');
            return 1;
        }

        Excel::import(new UsersImport, $file);

        $this->info('Users imported successfully.');
        return 0;
    }
}
