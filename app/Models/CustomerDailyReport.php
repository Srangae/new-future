<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerDailyReport extends Model
{
    use HasFactory;

    protected $table = 'customer_daily_report';

    protected $fillable = [
        'customer_id',
        'date',
        'sales_count',
        'sales_total'
    ];

    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
