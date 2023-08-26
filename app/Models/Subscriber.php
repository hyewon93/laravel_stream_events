<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Subscriber extends Model
{
    use HasFactory;

    protected $table = 'subscribers';
    protected $primaryKey = 'id';

    public static function getTotalRevenue()
    {
        $totalRevenue = 0;

        try {
            $result = Subscriber::select(DB::raw('
                                    subscription_tier, 
                                    CASE 
                                        WHEN subscription_tier = 1 THEN (5 * count(*))
                                        WHEN subscription_tier = 2 THEN (10 * count(*))
                                        WHEN subscription_tier = 3 THEN (15 * count(*))
                                        ELSE 0
                                    END AS revenue
                                '))
                                ->where('created_at', '>', now()->subDays(30)->endOfDay())
                                ->groupBy('subscription_tier')
                                ->get();
                    
            foreach($result as $revenue)
            {
                $totalRevenue += $revenue->revenue;
            }
                                
        } catch (Exception $e) {
            report($e);

            return 0;
        }

        return $totalRevenue;
    }
}
