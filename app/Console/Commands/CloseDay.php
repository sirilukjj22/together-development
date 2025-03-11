<?php

namespace App\Console\Commands;

use App\Models\Harmony_SMS_alerts;
use App\Models\Harmony_tb_close_days;
use App\Models\SMS_alerts;
use App\Models\TB_close_days;
use Illuminate\Console\Command;

class CloseDay extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:closeday';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Close Day';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $date_start = date('Y-m-d 21:00:00', strtotime("-2 day", strtotime(date('Y-m-d'))));
        $date_end = date('Y-m-d 20:59:59', strtotime("-1 day", strtotime(date('Y-m-d'))));
        $adate = date('Y-m-d', strtotime("-1 day", strtotime(date('Y-m-d'))));

        ## SMS
        SMS_alerts::whereBetween('date', [$date_start, $date_end])->whereNull('date_into')->update([
            'close_day' => 1
        ]);

        Harmony_SMS_alerts::whereBetween('date', [$date_start, $date_end])->whereNull('date_into')->update([
            'close_day' => 1
        ]);

        ## Transfer
        SMS_alerts::whereDate('date_into', $adate)->where('transfer_status', 1)
            ->orWhereDate('date', $adate)->where('transfer_status', 1)
            ->orWhere('status', 4)->where('split_status', 0)->whereDate('date_into', $adate)
            ->orWhereDate('date', $adate)->where('status', 4)->where('split_status', 0)
            ->update([
                'close_day' => 1
            ]);

        Harmony_SMS_alerts::whereDate('date_into', $adate)->where('transfer_status', 1)
            ->orWhereDate('date', $adate)->where('transfer_status', 1)
            ->orWhere('status', 4)->where('split_status', 0)->whereDate('date_into', $adate)
            ->orWhereDate('date', $adate)->where('status', 4)->where('split_status', 0)
            ->update([
                'close_day' => 1
            ]);

        ## Split
        SMS_alerts::whereDate('date_into', $adate)->where('split_status', 1)->update([
            'close_day' => 1
        ]);

        Harmony_SMS_alerts::whereDate('date_into', $adate)->where('split_status', 1)->update([
            'close_day' => 1
        ]);

        ## Save Close Day
        TB_close_days::create([
            'date' => $adate,
            'status' => 1
        ]);

        Harmony_tb_close_days::create([
            'date' => $adate,
            'status' => 1
        ]);

        return Command::SUCCESS;
    }
}
