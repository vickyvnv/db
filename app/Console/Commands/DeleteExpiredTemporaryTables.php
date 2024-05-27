<?php

namespace App\Console\Commands;

use App\Models\TemporaryTable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class DeleteExpiredTemporaryTables extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'temporary-tables:delete-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete expired temporary tables';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Get the expired temporary tables
        $expiredTables = TemporaryTable::where('drop_date', '<=', now())->get();

        foreach ($expiredTables as $table) {
            // Drop the temporary table from the database
            Schema::dropIfExists($table->table_name);

            // Delete the temporary table record from the database
            $table->delete();

            $this->info('Deleted expired temporary table: ' . $table->table_name);
        }

        $this->info('Expired temporary tables deleted successfully.');
    }
}