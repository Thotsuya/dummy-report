<?php

namespace App\Services;

use App\Models\Chart;
use App\Models\Order;
use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReportingService
{

    public ?string $fromDate;
    public ?string $toDate;


    public function __construct(
        ?string $fromDate,
        ?string $toDate,
    )
    {
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
    }

    public function generateReport(string $reportType){
        return match ($reportType) {
            'demographics' => $this->generateDemographicsReport(),
            'order_summaries' => $this->orderSummaries(),
            'lifetime_value' => $this->lifetimeValue(),
            'product_sales' => $this->productSales(),
            'churn_analysis' => $this->churnAnalysis(),
            'revenue_growth' => $this->revenueGrowth(),
            'geographic_distribution' => $this->geographicDistribution(),
            default => 'Invalid report type',
        };
    }

    public function generateDemographicsReport() : void
    {

        //        Breakdown of users by age, gender, country
        //        Count of users registered per month or year.
        //        Active vs inactive accounts.
        $report = Report::updateOrCreate([
            'title' => 'Demographics Report',
            'description' => 'Breakdown of users by age, gender, country, and province. Count of users registered per month or year. Active vs inactive accounts.',
        ],[
            'from_date' => $this->fromDate,
            'to_date' => $this->toDate,
        ]);

        //Generate the chart data
        $usersByAgeGroup = \DB::table('users')
            ->select(\DB::raw("
            CASE
                WHEN age BETWEEN 18 AND 25 THEN '18-25'
                WHEN age BETWEEN 26 AND 35 THEN '26-35'
                WHEN age BETWEEN 36 AND 50 THEN '36-50'
                ELSE '51+'
            END as age_group,
            count(*) as count
        "))
            ->groupBy('age_group')
            ->get();



        Chart::updateOrCreate([
            'report_id' => $report->id,
            'chart_name' => 'Users by Age',
            'chart_type' => 'bar',
        ],[
            'chart_labels' => $usersByAgeGroup->pluck('age_group')->toArray(), //['18-25', '26-35', '36-50', '51+']
            'chart_values' => $usersByAgeGroup->pluck('count')->toArray(), //[10, 20, 30, 40]
        ]);


        // Users by Gender
        $usersByGender = \DB::table('users')
            ->select(\DB::raw('gender, count(*) as count'))
            ->groupBy('gender')
            ->get();

        Chart::updateOrCreate([
            'report_id' => $report->id,
            'chart_name' => 'Users by Gender',
            'chart_type' => 'donut',
        ],[
            'chart_labels' => $usersByGender->pluck('gender')->toArray(),
            'chart_values' => $usersByGender->pluck('count')->toArray(),
        ]);

        // Users by Country ( Latest 5 months )
        $usersByCountry = \DB::table('users')
            ->select(\DB::raw('country, count(*) as count'))
            ->groupBy('country')
            ->limit(10)
            ->get();

        Chart::updateOrCreate([
            'report_id' => $report->id,
            'chart_name' => 'Users by Country',
            'chart_type' => 'bar',
        ],[
            'chart_labels' => $usersByCountry->pluck('country')->toArray(),
            'chart_values' => $usersByCountry->pluck('count')->toArray()
        ]);


        // Count of users registered per month and year, i.e February 2021, March 2021, etc.
        $usersRegisteredPerMonth = \DB::table('users')
            ->select(\DB::raw("DATE_FORMAT(registered_at, '%Y-%m') as month, count(*) as count"))
            ->groupBy('month')
            ->limit(10)
            ->get();

        Chart::updateOrCreate([
            'report_id' => $report->id,
            'chart_name' => 'Users Registered per Month',
            'chart_type' => 'line',
        ],[
            'chart_labels' => $usersRegisteredPerMonth->pluck('month')->toArray(),
            'chart_values' => $usersRegisteredPerMonth->pluck('count')->toArray(),
        ]);


        // Active vs Inactive accounts
        $activeVsInactiveAccounts = \DB::table('users')
            ->select(\DB::raw('account_status, count(*) as count'))
            ->groupBy('account_status')
            ->get();

        Chart::updateOrCreate([
            'report_id' => $report->id,
            'chart_name' => 'Active vs Inactive Accounts',
            'chart_type' => 'donut',
        ],[
            'chart_labels' => $activeVsInactiveAccounts->pluck('account_status')->toArray(),
            'chart_values' => $activeVsInactiveAccounts->pluck('count')->toArray(),
        ]);


    }

    public function orderSummaries() : void
    {
        //Order Summaries:
        //Total number of orders placed per day, week, month, or year.
        //Total sales value over a given period.
        //Orders by payment method or order status.

        $report = Report::updateOrCreate([
            'title' => 'Order Summaries',
            'description' => 'Total number of orders placed per day, week, month, or year. Total sales value over a given period. Orders by payment method or order status.',
        ],[
            'from_date' => $this->fromDate,
            'to_date' => $this->toDate,
        ]);

        //Total number of orders placed per month, i.e February 2021, March 2021, etc.
        $ordersPerMonth = \DB::table('orders')
            ->select(\DB::raw("DATE_FORMAT(purchase_date, '%Y-%m') as month, count(*) as count"))
            ->groupBy('month')
            ->get();

        Chart::updateOrCreate([
            'report_id' => $report->id,
            'chart_name' => 'Orders per Month',
            'chart_type' => 'line',
        ],[
            'chart_labels' => $ordersPerMonth->pluck('month')->toArray(),
            'chart_values' => $ordersPerMonth->pluck('count')->toArray(),
        ]);

        //Total sales value over a given period , per year in this case
        $salesPerYear = \DB::table('orders')
            ->select(\DB::raw("DATE_FORMAT(purchase_date, '%Y') as year, sum(total) as total_sales"))
            ->groupBy('year')
            ->get();

        Chart::updateOrCreate([
            'report_id' => $report->id,
            'chart_name' => 'Total Sales per Year',
            'chart_type' => 'bar',
        ],[
            'chart_labels' => $salesPerYear->pluck('year')->toArray(),
            'chart_values' => $salesPerYear->pluck('total_sales')->toArray(),
        ]);

        //Orders by payment method
        $ordersByPaymentMethod = \DB::table('orders')
            ->select(\DB::raw('payment_method, count(*) as count'))
            ->groupBy('payment_method')
            ->get();

        Chart::updateOrCreate([
            'report_id' => $report->id,
            'chart_name' => 'Orders by Payment Method',
            'chart_type' => 'donut',
        ],[
            'chart_labels' => $ordersByPaymentMethod->pluck('payment_method')->toArray(),
            'chart_values' => $ordersByPaymentMethod->pluck('count')->toArray(),
        ]);

        //Orders by order status
        $ordersByOrderStatus = \DB::table('orders')
            ->select(\DB::raw('order_status, count(*) as count'))
            ->groupBy('order_status')
            ->get();

        Chart::updateOrCreate([
            'report_id' => $report->id,
            'chart_name' => 'Orders by Order Status',
            'chart_type' => 'donut',
        ],[
            'chart_labels' => $ordersByOrderStatus->pluck('order_status')->toArray(),
            'chart_values' => $ordersByOrderStatus->pluck('count')->toArray(),
        ]);

    }

    public function lifetimeValue() : void
    {
        $report = Report::updateOrCreate([
            'title' => 'Lifetime Value',
            'description' => 'List of users ranked by total spending on the platform. Average order value by user. Average number of orders placed by user.',
        ],[
            'from_date' => $this->fromDate,
            'to_date' => $this->toDate,
        ]);

        //List of users ranked by total spending on the platform. return name and total,
        //i.e John Doe - $100, Jane Doe - $200
        $usersByTotalSpending = User::query()
            ->with('orders')
            ->withSum('orders', 'total')
            ->orderByDesc('orders_sum_total')
            ->limit(7)
            ->get();

        Chart::updateOrCreate([
            'report_id' => $report->id,
            'chart_name' => 'Users by Total Spending',
            'chart_type' => 'bar',
        ],[
            'chart_labels' => $usersByTotalSpending->pluck('name')->toArray(),
            'chart_values' => $usersByTotalSpending->pluck('orders_sum_total')->toArray(),
        ]);


        //Average order value
        $averageOrderValue = \DB::table('orders')
            ->select(\DB::raw('avg(total) as average_order_value'))
            ->get();

        Chart::updateOrCreate([
            'report_id' => $report->id,
            'chart_name' => 'Average Order Value by User',
            'chart_type' => 'static',
        ],[
            'chart_labels' => [""],
            'chart_values' => $averageOrderValue->pluck('average_order_value')->toArray(),
        ]);

    }

    public function productSales() : void
    {
        //Product Sales Report:
        //Top-selling product categories or specific products.
        //Orders segmented by the number of products.
        //Products frequently purchased together.

        $report = Report::updateOrCreate([
            'title' => 'Product Sales',
            'description' => 'Top-selling product categories or specific products. Orders segmented by the number of products. Products frequently purchased together.',
        ],[
            'from_date' => $this->fromDate,
            'to_date' => $this->toDate,
        ]);

        //Top-selling product categories or specific products.
        $topSellingProducts = \DB::table('orders')
            //product_category is a column in the orders table, and amount_of_products is the number of products in the order.
            ->select(\DB::raw('product_category, sum(amount_of_products) as total_units_sold'))
            ->groupBy('product_category')
            ->orderBy('total_units_sold', 'desc')
            ->get();

        Chart::updateOrCreate([
            'report_id' => $report->id,
            'chart_name' => 'Top Selling Products',
            'chart_type' => 'bar',
        ],[
            'chart_labels' => $topSellingProducts->pluck('product_category')->toArray(),
            'chart_values' => $topSellingProducts->pluck('total_units_sold')->toArray(),
        ]);

        //Last 20 Orders segmented by the number of products.
        $ordersByProductCount = \DB::table('orders')
            ->select(\DB::raw('amount_of_products, count(*) as count'))
            ->groupBy('amount_of_products')
            ->orderBy('amount_of_products', 'asc')
            ->limit(20)
            ->get();


        Chart::updateOrCreate([
            'report_id' => $report->id,
            'chart_name' => 'Orders by Product Count',
            'chart_type' => 'bar',
        ],[
            'chart_labels' => $ordersByProductCount->pluck('amount_of_products')->toArray(),
            'chart_values' => $ordersByProductCount->pluck('count')->toArray(),
        ]);

    }

    public function churnAnalysis() : void
    {
        //Churn Analysis Report:
        //Users who haven’t placed an order in the last 3, 6, or 12 months.
        //Inactive users with a certain account age.

        $report = Report::updateOrCreate([
            'title' => 'Churn Analysis',
            'description' => 'Users who haven’t placed an order in the last 3, 6, or 12 months. Inactive users with a certain account age.',
        ],[
            'from_date' => $this->fromDate,
            'to_date' => $this->toDate,
        ]);

        //Users who haven’t placed an order in the last 3, 6, or 12 months.
        $churnedUsers = \DB::table('users')
            ->select(\DB::raw('id, count(*) as count'))
            ->whereNotIn('id', function($query){
                $query->select('user_id')
                    ->from('orders')
                    ->where('purchase_date', '>=', now()->subMonths(3));
            })
            ->groupBy('id')
            ->get();

        Chart::updateOrCreate([
            'report_id' => $report->id,
            'chart_name' => 'Churned Users',
            'chart_type' => 'bar',
        ],[
            'chart_labels' => $churnedUsers->pluck('id')->toArray(),
            'chart_values' => $churnedUsers->pluck('count')->toArray(),
        ]);

    }

    public function revenueGrowth() : void
    {
//        Revenue Growth:
//Monthly or yearly revenue comparison.
//Average order value trends over time.

        $report = Report::updateOrCreate([
            'title' => 'Revenue Growth',
            'description' => 'Monthly or yearly revenue comparison. Average order value trends over time.',
        ],[
            'from_date' => $this->fromDate,
            'to_date' => $this->toDate,
        ]);

        //Monthly or yearly revenue comparison.
        $revenueComparison = \DB::table('orders')
            ->select(\DB::raw("DATE_FORMAT(purchase_date, '%Y-%m') as month, sum(total) as total_sales"))
            ->groupBy('month')
            ->get();

        Chart::updateOrCreate([
            'report_id' => $report->id,
            'chart_name' => 'Revenue Comparison',
            'chart_type' => 'line',
        ],[
            'chart_labels' => $revenueComparison->pluck('month')->toArray(),
            'chart_values' => $revenueComparison->pluck('total_sales')->toArray(),
        ]);

        //Average order value trends over time.
        $averageOrderValueTrends = \DB::table('orders')
            ->select(\DB::raw("DATE_FORMAT(purchase_date, '%Y-%m') as month, avg(total) as average_order_value"))
            ->groupBy('month')
            ->get();

        Chart::updateOrCreate([
            'report_id' => $report->id,
            'chart_name' => 'Average Order Value Trends',
            'chart_type' => 'line',
        ],[
            'chart_labels' => $averageOrderValueTrends->pluck('month')->toArray(),
            'chart_values' => $averageOrderValueTrends->pluck('average_order_value')->toArray(),
        ]);
    }

    //Geographical Reports:
    //Sales distribution by country
    //User registration trends based on geography.
    public function geographicDistribution() : void
    {
        $report = Report::updateOrCreate([
            'title' => 'Geographic Distribution',
            'description' => 'Sales distribution by country. User registration trends based on geography.',
        ],[
            'from_date' => $this->fromDate,
            'to_date' => $this->toDate,
        ]);

        //Top 5 countries by sales
        $top5CountriesBySales = \DB::table('users')
            ->select(\DB::raw('country, sum(total) as total_sales'))
            ->join('orders', 'users.id', '=', 'orders.user_id')
            ->groupBy('country')
            ->orderBy('total_sales', 'desc')
            ->limit(10)
            ->get();

        Chart::updateOrCreate([
            'report_id' => $report->id,
            'chart_name' => 'Top 10 Countries by Sales',
            'chart_type' => 'donut',
        ],[
            'chart_labels' => $top5CountriesBySales->pluck('country')->toArray(),
            'chart_values' => $top5CountriesBySales->pluck('total_sales')->toArray(),
        ]);

        //Top countries by user count
        $topCountriesByUserCount = \DB::table('users')
            ->select(\DB::raw('country, count(*) as user_count'))
            ->groupBy('country')
            ->orderBy('user_count', 'desc')
            ->limit(10)/**/
            ->get();

        Chart::updateOrCreate([
            'report_id' => $report->id,
            'chart_name' => 'Top 10 Countries by User Count',
            'chart_type' => 'donut',
        ],[
            'chart_labels' => $topCountriesByUserCount->pluck('country')->toArray(),
            'chart_values' => $topCountriesByUserCount->pluck('user_count')->toArray(),
        ]);
    }
}
