<?php

namespace App\Console\Commands;

use App\Services\ReportingService;
use Illuminate\Console\Command;

class GenerateUserReports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-user-reports';

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
        $this->info('Generating user reports...');

        $reports = new ReportingService(null,null);

        $this->info('Generating Demographics report...');
        $reports->generateReport('demographics');

        $this->info('Generating Order Summaries report...');
        $reports->generateReport('order_summaries');

        $this->info('Generating Order Status report...');
        $reports->generateReport('lifetime_value');

        $this->info('Generating Product Categories report...');
        $reports->generateReport('product_sales');

        //revenue_growth
        $this->info('Generating Revenue Growth report...');
        $reports->generateReport('revenue_growth');

        //geographic_distribution
        $this->info('Generating Geographic Distribution report...');
        $reports->generateReport('geographic_distribution');


    }
}
