{{-- EXTEND --}}
@extends('backstage.layout')

{{-- VARS --}}
@section('title', 'Manage Book - Active Events')

@section('HTML-js')
    <script type="text/javascript" src="{{ mix('/backstage/js/book.js') }}"></script>
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
                <th scope="col" width="50px">External Id</th>
                <th scope="col" width="50px">Start time</th>
                <th scope="col" width="80px">Status</th>
                <th scope="col" width="100px">Home</th>
                <th scope="col" width="80px">Home Score</th>
                <th scope="col" width="80px">Away</th>
                <th scope="col" width="80px">Away Score</th>
                <th scope="col" width="100px"></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($events as $event)
                <?php /** @var \App\Domain\ApiEvent $event */ ?>
                <tr>
                    <th scope="row" class="text-center"></th>
                    <td class="text-truncate">{{ $event->getApiId() }}</td>
                    <td class="text-truncate">@{{ @json($event->getStartsAt()->format(DateTime::ATOM)) | toDateTime }}</td>
                    <td class="text-truncate">{{ ucwords(str_replace('_', ' ', $event->getTimeStatus())) }}</td>
                    <td class="text-truncate">{{ $event->getTeamHome() }}</td>
                    <td class="text-truncate">{{ $event->getScoreHome() ?? '-' }}</td>
                    <td class="text-truncate">{{ $event->getTeamAway() }}</td>
                    <td class="text-truncate">{{ $event->getScoreAway() ?? '-' }}</td>
                    <td class="text-right">
                        <button
                            type="button"
                            class="btn btn-outline-success btn-sm"
                            title="Move to in play"
                            @click='openStartModal(@json($event->getId()), @json($event->getTeamHome() . ' vs ' . $event->getTeamAway() . ' (' . $event->getApiId() . ')'))'
                        >
                            <i class="fas fa-caret-square-right"></i>
                        </button>
                        <button
                            type="button"
                            class="btn btn-outline-primary btn-sm"
                            title="Enter result"
                            @click='openFinishModal(@json($event->getId()), @json($event->getTeamHome() . ' vs ' . $event->getTeamAway() . ' (' . $event->getApiId() . ')'))'
                        >
                            <i class="fas fa-flag-checkered"></i>
                        </button>
                        <button
                            type="button"
                            class="btn btn-outline-danger btn-sm"
                            title="Cancel Event"
                            @click='openCancelModal(@json($event->getId()), @json($event->getTeamHome() . ' vs ' . $event->getTeamAway() . ' (' . $event->getApiId() . ')'))'
                        >
                            <i class="fas fa-calendar-times"></i>
                        </button>

                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <modal-cancel
        v-model="cancelModalId"
        :text-description="cancelModalDesc"
        @@destroy="cancelEvent(cancelModalId)"
    ></modal-cancel>
    <modal-start
        v-model="startModalId"
        :text-description="startModalDesc"
        @@destroy="startEvent(startModalId)"
    ></modal-start>
    <modal-finish
        v-model="finishModalId"
        :text-description="finishModalDesc"
        @@destroy="finishEvent"
    ></modal-finish>
@endsection
