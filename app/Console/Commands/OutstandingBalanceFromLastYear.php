<?php

namespace App\Console\Commands;

use App\Models\Revenues;
use App\Models\TB_outstanding_balance;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class OutstandingBalanceFromLastYear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:outstandingbalance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Outstanding Balance From Last Year';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $lastYear = date('Y', strtotime('-1 year'));
        
        if (date('Y-m-d') == date('Y-01-01')) {

            $fromLastYear = date('Y-m-d', strtotime(date($lastYear.'-01-01')));
            $toLastYear = date('Y-m-d', strtotime(date($lastYear.'-12-31')));

            $total_agoda = Revenues::whereBetween('date', [$fromLastYear, $toLastYear])->sum('total_credit_agoda');
            $total_elexa = Revenues::whereBetween('date', [$fromLastYear, $toLastYear])->sum('total_elexa');

            $agoda = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 5)
                ->whereBetween('revenue.date', [$fromLastYear, $toLastYear])
                ->select(DB::raw("SUM(revenue_credit.agoda_charge) as agoda_charge, SUM(revenue_credit.agoda_outstanding) as agoda_outstanding"))
                ->first();

            $elexa = Revenues::leftjoin('revenue_credit', 'revenue.id', 'revenue_credit.revenue_id')->where('revenue_credit.status', 8)
                ->whereBetween('revenue.date', [$fromLastYear, $toLastYear])
                ->select(DB::raw("SUM(revenue_credit.ev_charge) as ev_charge, SUM(revenue_credit.ev_revenue) as ev_revenue"))
                ->first();

            TB_outstanding_balance::create([
                'year' => $lastYear,
                'agoda_balance' => !empty($agoda) ? ($total_agoda - $agoda->total_credit_agoda) : 0,
                'elexa_balance' => !empty($elexa) ? ($elexa->ev_revenue - $total_elexa) : 0
            ]);
        }

        return Command::SUCCESS;
    }
}
