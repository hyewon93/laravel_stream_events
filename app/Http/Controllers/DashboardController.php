<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Follower;
use App\Models\MerchSale;
use App\Models\Subscriber;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index() 
    {
        $totalRevenue = $this->getTotalRevenue();
        $totalFollowersCount = Follower::getTotalFollowers();
        $top3Items = MerchSale::getTop3Items();

        return view('dashboard', [
            "totalRevenue" => $totalRevenue,
            "totalFollowersCount" => $totalFollowersCount,
            "top3Items" => $top3Items
        ]);
    }

    public function getTotalRevenue() {
        $totalRevenue = [
            "USD" => 0,
            "CAD" => 0
        ];

        $totalSubscriberRevenues = Subscriber::getTotalRevenue();   // available currency: USD
        $totalDonationRevenues = Donation::getTotalRevenue();       // available currency: USD, CAD
        $totalMerchSaleRevenues = MerchSale::getTotalRevenue();     // available currency: USD

        if(!empty($totalDonationRevenues)) {
            foreach($totalDonationRevenues as $donationRevenue) {
                if($donationRevenue->currency == "USD") {
                    $donationRevenue->revenue += $totalSubscriberRevenues + $totalMerchSaleRevenues;
                } 

                $totalRevenue[$donationRevenue->currency] = number_format($donationRevenue->revenue, 2);
            }

        } else {
            $totalRevenue["USD"] = number_format(($totalSubscriberRevenues + $totalMerchSaleRevenues), 2);
        }

        return $totalRevenue;
    }
}
