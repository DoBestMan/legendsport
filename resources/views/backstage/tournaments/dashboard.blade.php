{{-- EXTEND --}}
    @extends('backstage.layout')

{{-- VARS --}}
    @section('title', 'Tournament Payouts')

{{-- HTML --}}
@section('HTML-main')
@parent

<div class="container">
    <div name="titleFrm" class="row">
        <div class="col">
            <h1 class="ui-title">@yield('title')</h1>
        </div>
    </div>

    <hr>
    <div class="row">
        <div class="col-sm-3">
            <div class="card ">
                <div class="card-header text-white bg-primary">Today</div>
                <div class="card-body">
                    <h5 class="card-title">Bot Winnings</h5>
                    <p class="card-text">${{ number_format($payoutBuckets['today']['bots'] / 100, 2) }}</p>
                    <h5 class="card-title">Player Winnings</h5>
                    <p class="card-text">${{ number_format($payoutBuckets['today']['players'] / 100, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card ">
                <div class="card-header text-white bg-info">This Week</div>
                <div class="card-body">
                    <h5 class="card-title">Bot Winnings</h5>
                    <p class="card-text">${{ number_format($payoutBuckets['wtd']['bots'] / 100, 2) }}</p>
                    <h5 class="card-title">Player Winnings</h5>
                    <p class="card-text">${{ number_format($payoutBuckets['wtd']['players'] / 100, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card ">
                <div class="card-header text-white bg-secondary">This Month</div>
                <div class="card-body">
                    <h5 class="card-title">Bot Winnings</h5>
                    <p class="card-text">${{ number_format($payoutBuckets['mtd']['bots'] / 100, 2) }}</p>
                    <h5 class="card-title">Player Winnings</h5>
                    <p class="card-text">${{ number_format($payoutBuckets['mtd']['players'] / 100, 2) }}</p>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card">
                <div class="card-header bg-light">Last Month</div>
                <div class="card-body">
                    <h5 class="card-title">Bot Winnings</h5>
                    <p class="card-text">${{ number_format($payoutBuckets['lastMonth']['bots'] / 100, 2) }}</p>
                    <h5 class="card-title">Player Winnings</h5>
                    <p class="card-text">${{ number_format($payoutBuckets['lastMonth']['players'] / 100, 2) }}</p>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
