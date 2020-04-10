{{-- EXTEND --}}
    @extends('backstage.tournaments.layout')

{{-- VARS --}}
    @section('title', 'Tournaments')

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

    <div name="tableFrm" class="row">
        <div class="col">
            <div class="table-responsive">
                <table class="table table-sm table-light table-striped table-borderless table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col" class="text-center" width="25px">#</th>
                            <th scope="col" width="160px">Name</th>
                            <th scope="col" width="50px">Type</th>
                            <th scope="col" width="100px">Players limit</th>
                            <th scope="col" width="60px">Buy-in</th>
                            <th scope="col" width="80px">Prize pool</th>
                            <th scope="col" width="50px">Commission</th>
                            <th scope="col" width="80px">Chips</th>
                            <th scope="col" width="100px">State</th>
                            <th scope="col" width="150px"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tournaments as $tournament)
                            <tr>
                                <th scope="row" class="text-center">{{ $numFirstItemPage++ }}</th>
                                <td class="text-truncate">{{ $tournament->name }}</td>
                                <td class="text-truncate">{{ $tournament->type }}</td>
                                <td class="text-truncate">{{ $tournament->players_limit }}</td>
                                <td class="text-truncate">@{{ @json($tournament->buy_in) | formatDollars }}</td>
                                <td class="text-truncate">{{ $tournament->prize_pool['type'] }}</td>
                                <td class="text-truncate">@{{ @json($tournament->commission) | formatDollars }}</td>
                                <td class="text-truncate">@{{ @json($tournament->chips) | formatChip }}</td>
                                <td class="text-truncate">{{ $tournament->state }}</td>
                                <td class="text-right">
                                    <a
                                        class="btn btn-outline-primary btn-sm"
                                        title="View"
                                        href="{{ route('tournaments.show', $tournament) }}"
                                    >
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a
                                        class="btn btn-outline-dark btn-sm"
                                        title="Editar"
                                        href="{{ route('tournaments.edit', $tournament) }}"
                                    >
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>

                                    <button
                                        type="button"
                                        class="btn btn-outline-danger btn-sm"
                                        title="Delete"
                                        @click='openDeleteModal(@json($tournament->id), @json($tournament->name))'
                                    >
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{ $tournaments->links() }}
                <hr>
            </div>
        </div>
    </div>

    <div id="buttonsFrm" class="row">
        <div class="col-3 col-lg-2">
            <a class="btn btn-dark btn-block" href="{{ route('tournaments.create') }}">
                Create
            </a>
        </div>
    </div>
</div>

<modal-delete
    v-model="modalDeleteId"
    :text-description="modalDeleteDescription"
    @@destroy="deleteTournament(modalDeleteId)"
></modal-delete>
@endsection
