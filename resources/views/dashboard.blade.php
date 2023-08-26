<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="dashboard-section grid-container">
                <div class="grid-item bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Total Revenue</h2>
                    <div class="grid-item-inner section-revenue">
                        @foreach($totalRevenue as $currency => $revenue)
                        <div class="display-flex margin-bottom-1rem">
                            <div class="total-revenue-currency">{{ $currency }}</div>
                            <div class="total-revenue">$ {{ $revenue }}</div>
                        </div>
                        @endforeach 
                    </div>
                </div>
                <div class="grid-item bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Total Followers</h2>
                    <div class="grid-item-inner section-followers">
                        {{ $totalFollowersCount }}
                    </div>
                </div>
                <div class="grid-item bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">Top 3 Items</h2>
                    <div class="grid-item-inner">
                        @foreach($top3Items as $item)
                        <div class="display-flex margin-bottom-1rem">
                            <div class="top-item-name"><span class="top-item-lanking">Top {{ $loop->index + 1 }}</span> {{ $item->item_name }}</div>
                            <div class="top-item-count">{{ $item->count }}</div>
                        </div>
                        @endforeach    
                    </div>
                </div>
            </div>
            <div class="dashboard-section padding-1rem bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight margin-bottom-1rem">History</h2>
            </div>
        </div>
    </div>
</x-app-layout>
