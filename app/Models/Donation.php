<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Donation extends Model
{
    use HasFactory;

    protected $table = 'donations';
    protected $primaryKey = 'id';

    public static function getTotalRevenue() 
    {
        try {
            $totalRevenue = Donation::select(DB::raw('SUM(amount) as revenue, currency'))
                                ->where('created_at', '>', now()->subDays(30)->endOfDay())
                                ->groupBy('currency')
                                ->get();

        } catch (Exception $e) {
            report($e);

            return 0;
        }

        return $totalRevenue;
    }

    public static function updateRead($id) 
    {
        try {
            $result = Donation::where('id', $id)
                                ->update(['read' => 1]);

        } catch (Exception $e) {
            report($e);

            return 0;
        }
        
        return $result;
    }
}