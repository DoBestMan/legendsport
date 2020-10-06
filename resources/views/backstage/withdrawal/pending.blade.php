{{-- EXTEND --}}
@extends('backstage.layout')

{{-- VARS --}}
@section('title', 'Manage Withdrawals - Pending requests')

@section('HTML-js')
    <script type="text/javascript" src="{{ mix('/backstage/js/withdrawal.js') }}"></script>
@endsection

{{-- HTML --}}
@section('HTML-main')
    @parent

    <div class="container">
        <div name="titleFrm" class="row">
            <div class="col">
                <h1 class="ui-title">@yield('title')</h1>
            </div>
        </div>

        <hr/>

        <table class="table table-sm table-light table-striped table-borderless table-hover">
            <thead class="thead-dark">
            <tr>
                <th scope="col" class="text-center" width="5px"></th>
                <th scope="col" width="50px">Id</th>
                <th scope="col" width="50px">Amount</th>
                <th scope="col" width="80px">BTC Address</th>
                <th scope="col" width="100px">Name</th>
                <th scope="col" width="80px">Email</th>
                <th scope="col" width="100px"></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($withdrawals as $withdrawal)
                <?php /** @var \App\Domain\Withdrawal $withdrawal */ ?>
                <tr>
                    <th scope="row" class="text-center"></th>
                    <td class="text-truncate">{{ $withdrawal->getId() }}</td>
                    <td class="text-truncate">$ {{ number_format($withdrawal->getAmount() / 100, 2) }}</td>
                    <td class="text-truncate">{{ $withdrawal->getBtcAddress() }}</td>
                    <td class="text-truncate">{{ $withdrawal->getUser()->getName() }}</td>
                    <td class="text-truncate">{{ $withdrawal->getUser()->getEmail() }}</td>
                    <td class="text-right">
                        <button
                            type="button"
                            class="btn btn-outline-success btn-sm"
                            title="Process payment"
                            @click='openProcessModal(@json($withdrawal->getId()), @json('$' . number_format($withdrawal->getAmount() / 100, 2) . ' to ' . $withdrawal->getBtcAddress()))'
                        >
                            <i class="fas fa-money-bill-wave"></i>
                        </button>

                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <modal-process
        v-model="processModalId"
        :text-description="processModalDesc"
        @@destroy="processPayment(processModalId)"
    ></modal-process>
@endsection
