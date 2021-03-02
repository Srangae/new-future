<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Sale extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'customer_id',
        'total',
        'date'
    ];

    protected $casts = [
        'date' => 'datetime:d-m-Y'
    ];

    public function scopeDailySalesReport()
    {
        return DB::table('sales')->select(DB::raw("FROM_UNIXTIME(date, '%y-%m-%d') as sales_date, SUM(total) as sales_total, COUNT(id) as sales_count"))->groupBy('sales_date')->get();
    }

    public function scopeCustomerDailySalesReport()
    {
        return DB::table('sales')->select(DB::raw("FROM_UNIXTIME(date, '%y-%m-%d') as sales_date, SUM(total) as sales_total, COUNT(id) as sales_count, customer_id"))->groupBy('sales_date','customer_id')->orderBy('customer_id')->get();

    }

}
