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

    public function getHistory() 
    {

        $followers = Follower::select('id as type_id', 'read', 'created_at')
                    ->selectRaw('? as type', ["follower"])
                    ->selectRaw('name as data_1')
                    ->selectRaw('null as data_2')
                    ->selectRaw('null as data_3')
                    ->selectRaw('null as data_4');

        $subscribers = Subscriber::select('id as type_id', 'read', 'created_at')
                    ->selectRaw('? as type', ["subscriber"])
                    ->selectRaw('name as data_1')
                    ->selectRaw('subscription_tier as data_2')
                    ->selectRaw('null as data_3')
                    ->selectRaw('null as data_4');

        $donations = Donation::select('id as type_id', 'read', 'created_at')
                    ->selectRaw('? as type', ["donation"])
                    ->selectRaw('name as data_1')
                    ->selectRaw('CONCAT(amount, " ", currency) as data_2')
                    ->selectRaw('message as data_3')
                    ->selectRaw('null as data_4');

        $merchSales = MerchSale::select('id as type_id', 'read', 'created_at')
                    ->selectRaw('? as type', ["merchSale"])
                    ->selectRaw('name as data_1')
                    ->selectRaw('item_name as data_2')
                    ->selectRaw('amount as data_3')
                    ->selectRaw('amount * price as data_4')
                    ->union($followers)
                    ->union($subscribers)
                    ->union($donations)
                    ->orderBy('created_at', 'desc')
                    ->paginate(100);

        $historyRecords = '';
        foreach ($merchSales as $result) {

            switch($result->type) {
                case "follower":
                    $historyRecords .='
                        <div 
                            class="history-row' . ($result->read ? ' read' : '' ) . '"
                            data-id="' . $result->type_id . '"
                            data-type="' . $result->type . '"
                        >
                            <span class="history-user-name">' . $result->data_1 . '</span> followed you!
                        </div>';
                    break;
                case "subscriber":
                    $historyRecords .='
                        <div 
                            class="history-row' . ($result->read ? ' read' : '' ) . '"
                            data-id="' . $result->type_id . '"
                            data-type="' . $result->type . '"
                        >
                            <span class="history-user-name">' . $result->data_1 . '</span> (Tier ' . $result->data_2 . ') subscribed to you!
                        </div>';
                    break;
                case "donation":
                    $historyRecords .='
                        <div 
                            class="history-row' . ($result->read ? ' read' : '' ) . '"
                            data-id="' . $result->type_id . '"
                            data-type="' . $result->type . '"
                        >
                            <span class="history-user-name">' . $result->data_1 . '</span> donated ' . $result->data_2 . ' to you!<br>
                            <span class="history-donation-message">"' . $result->data_3 . '"</span>
                        </div>';
                    break;
                case "merchSale":
                    if($result->data_3 > 1) {
                        $historyRecords .='
                            <div 
                                class="history-row' . ($result->read ? ' read' : '' ) . '"
                                data-id="' . $result->type_id . '"
                                data-type="' . $result->type . '"
                            >
                                <span class="history-user-name">' . $result->data_1 . '</span> bought some ' . $result->data_2 . ' from you for ' . $result->data_4 . '!
                            </div>';
                    } else {
                        $historyRecords .='
                            <div 
                                class="history-row' . ($result->read ? ' read' : '' ) . '"
                                data-id="' . $result->type_id . '"
                                data-type="' . $result->type . '"
                            >
                                <span class="history-user-name">' . $result->data_1 . '</span> bought a(n) ' . $result->data_2 . ' from you for ' . $result->data_4 . '!
                            </div>';
                    }
                    
                    break;
                default:
                    $historyRecords .= '<div class="history-row">ERROR</div>';
                    break;
            }

        }

        return $historyRecords;
    }

    public function getTotalRevenue() 
    {
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

    public function updateRead(Request $request)
    {
    
        switch($request->input('type')) {
            case 'follower':
                $result = Follower::updateRead($request->input('id'));
                break;
            case 'donation':
                $result = Donation::updateRead($request->input('id'));
                break;
            case 'subscriber':
                $result = Subscriber::updateRead($request->input('id'));
                break;
            case 'merchSale':
                $result = MerchSale::updateRead($request->input('id'));
                break;
            default:
            $result = 0;
            break;
        }

        return $result;
    }
}
