<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DeleteOldFreelancerChecked extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'freelancer:delete-old';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete Freelancer_checked records older than 30 days';

    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dateThreshold = Carbon::now()->subDays(30);

        // ลบข้อมูลที่สร้างก่อนวันที่ที่กำหนด
        $deletedCount = Freelancer_checked::where('created_at', '<', $dateThreshold)->delete();

        $this->info("Deleted {$deletedCount} old Freelancer_checked records.");
    }
}
