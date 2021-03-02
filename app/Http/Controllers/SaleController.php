<?php

namespace App\Http\Controllers;

use App\Models\CustomerDailyReport;
use App\Models\DailySalesReport;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class SaleController extends Controller
{
    private CONST DAILY_SALES = 'daily_sales';
    private CONST CUSTOMER_SALES = 'customer_sales';
    /**
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $this->storeSalesData(Sale::DailySalesReport(), self::DAILY_SALES);
        $this->storeSalesData(Sale::CustomerDailySalesReport(), self::CUSTOMER_SALES);
    }

    private function storeSalesData(Collection $sales, $identifier)
    {
        if($identifier === self::DAILY_SALES) {
            $modifiedData = $this->customizeDailySalesData($sales);
        }
        if($identifier === self::CUSTOMER_SALES) {
            $modifiedData = $this->customizeCustomerDailySalesData($sales);
        }

        /**
         * Chunk collection before store in database to reduce number of SQL call
         *
         * Chunk(10) - only 4 Class for 31 records
         * No chunk - n Records of Calls
         */
        $dailySalesChunk = $modifiedData->chunk(1000);
        $dailySalesChunk->each(function($item) use($identifier){
            /**
             * Use insert rather than create
             * Create : do alot of extra field insert background
             * Insert : only insert the required field
             */
            if($identifier === self::DAILY_SALES) {
                DailySalesReport::insert($item->toArray());
            }

            if($identifier === self::CUSTOMER_SALES) {
                CustomerDailyReport::insert($item->toArray());
            }

        });
    }

    private function customizeDailySalesData(Collection $dailySales): Collection
    {
        return $dailySales->map(function($item){
            return [
                'date' => $item->sales_date,
                'sales_total' => $item->sales_total,
                'sales_count' => $item->sales_count
            ];
        });
    }

    private function customizeCustomerDailySalesData(Collection $customerDailySales): Collection
    {
        return $customerDailySales->map(function($item){
            return [
                'customer_id' => $item->customer_id,
                'date' => $item->sales_date,
                'sales_total' => $item->sales_total,
                'sales_count' => $item->sales_count
            ];
        });
    }
}
