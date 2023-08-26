<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MerchSale extends Model
{
    use HasFactory;

    protected $table = 'merch_sales';
    protected $primaryKey = 'id';

    public static function getTop3Items() 
    {
        try {
            $items = MerchSale::select(DB::raw('count(*) as count, item_name'))
                        ->where('created_at', '>', now()->subDays(30)->endOfDay())
                        ->orderBy('count', 'desc')
                        ->groupBy('item_name')
                        ->take(3)
                        ->get();

        } catch (Exception $e) {
            report($e);

            return false;
        }

        return $items;
    }

    public static function getTotalRevenue() 
    {
        try {
            $totalRevenue = MerchSale::select(DB::raw('SUM(price) as revenue'))
                                ->where('created_at', '>', now()->subDays(30)->endOfDay())
                                ->get();
        } catch (Exception $e) {
            report($e);

            return 0;
        }

        return $totalRevenue[0]->revenue;
    }

    public static function updateRead($id) 
    {
        try {
            $result = MerchSale::where('id', $id)
                                ->update(['read' => 1]);

        } catch (Exception $e) {
            report($e);

            return 0;
        }
        
        return $result;
    }
}
