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
                <div>
                    <div id="data-wrapper">
                        <!-- Results -->
                    </div>
                    <!-- Loading -->
                    <div class="auto-load text-center">
                        <svg version="1.1" id="L9" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            x="0px" y="0px" height="60" viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
                            <path fill="#000"
                                d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50">
                                <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="1s"
                                    from="0 50 50" to="360 50 50" repeatCount="indefinite" />
                            </path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>

        var ENDPOINT = "{{ url('/') }}";
        var page = 1;

        $(document).ready(function(){

            infinteLoadMore(page);

            $(window).scroll(function () {

                if ($(window).scrollTop() + $(window).height() + 10 >= $(document).height()) {

                    console.log("here");

                    page++;
                    infinteLoadMore(page);
                }
            });
        });

        function updateRead(target) {

            let id = $(target).attr("data-id");
            let type = $(target).attr("data-type");

            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: ENDPOINT + "/dashboard/update",
                data: {
                    "id": id,
                    "type": type
                },
                type: "post",
                dataType: "json",
                beforeSend: function () {
                    $('.auto-load').show();
                }
            })
            .done(function (response) {

                if(response > 0) {
                    $(target).addClass("read");
                }
            })
            .fail(function (jqXHR, ajaxOptions, thrownError) {
                console.log('There was an error to update data.');
            });
        }

        function infinteLoadMore(page) {
            $.ajax({
                url: ENDPOINT + "/dashboard/history?page=" + page,
                datatype: "html",
                type: "get",
                beforeSend: function () {
                    $('.auto-load').show();
                }
            })
            .done(function (response) {

                if (response.length == 0) {
                    $('.auto-load').html("We don't have more data to display.");
                    return;
                }
                $('.auto-load').hide();
                $("#data-wrapper").append(response);

                $('.history-row').on("click", function () {
                    if(!$(this).hasClass("read")) {

                        updateRead(this);
                    }
                    
                });
            })
            .fail(function (jqXHR, ajaxOptions, thrownError) {
                console.log('There was an error to get history.');
            });
        }
    </script>
</x-app-layout>
