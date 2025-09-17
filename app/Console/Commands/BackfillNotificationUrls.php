<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class BackfillNotificationUrls extends Command
{
    protected $signature = 'notifications:backfill-loan-urls';

    protected $description = 'Backfill notification URLs to point to loan detail pages based on loan_id';

    public function handle(): int
    {
        $count = 0;
        DB::table('notifications')->orderBy('created_at')->chunk(500, function($rows) use (&$count){
            foreach ($rows as $n) {
                $data = json_decode($n->data, true) ?? [];
                if ((!isset($data['url']) || str_contains($data['url'] ?? '', '/admin/loans')) && !empty($data['loan_id'])) {
                    $data['url'] = '/admin/loans/' . $data['loan_id'];
                    DB::table('notifications')->where('id',$n->id)->update(['data' => json_encode($data)]);
                    $count++;
                }
            }
        });
        $this->info("Updated {$count} notifications.");
        return Command::SUCCESS;
    }
}


