<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follower extends Model
{
    use HasFactory;

    protected $table = 'followers';
    protected $primaryKey = 'id';

    public static function getTotalFollowers() 
    {
        $totalFollowers = Follower::where('created_at', '>', now()->subDays(30)->endOfDay())->get();
        $totalFollowersCount = count($totalFollowers);

        try {
            $totalFollowers = Follower::where('created_at', '>', now()->subDays(30)->endOfDay())->get();
            $totalFollowersCount = count($totalFollowers);

        } catch (Exception $e) {
            report($e);

            return 0;
        }

        return $totalFollowersCount;
    }

    public static function updateRead($id) 
    {
        try {
            $result = Follower::where('id', $id)
                                ->update(['read' => 1]);

        } catch (Exception $e) {
            report($e);

            return 0;
        }
        
        return $result;
    }
}
