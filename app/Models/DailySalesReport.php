<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailySalesReport extends Model
{
    use HasFactory;

    protected $table = 'daily_sales_report';

    protected $fillable = [
        'date',
        'sales_count',
        'sales_total'
    ];
}
