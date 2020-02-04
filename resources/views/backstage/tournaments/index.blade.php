{{-- EXTEND --}}
    @extends('backstage.tournaments.layout')

{{-- VARS --}}
    @section('title', 'Tournaments')

{{-- BUTTONS --}}
    @section('buttonAdd_onclick', route('tournaments.create'))
    @section('buttonAdd_disabled', '')
{{-- HTML --}}
@section('HTML-main')
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
                                <td class="text-truncate">$ {{ $tournament->buy_in }}</td>
                                <td class="text-truncate">{{ $tournament->prize_pool['type'] }}</td>
                                <td class="text-truncate">${{ $tournament->commission }}</td>
                                <td class="text-truncate">{{ $tournament->chips }}</td>
                                <td class="text-truncate">{{ $tournament->state }}</td>
                                <td class="text-right">
                                    <button type="button"
                                        class="btn btn-outline-primary btn-sm"
                                        title="View"
                                        onclick="window.location='{{ route('tournaments.show', $tournament) }}'"
                                        >
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button type="button"
                                        class="btn btn-outline-dark btn-sm"
                                        title="Editar"
                                        onclick="window.location='{{ route('tournaments.edit', $tournament) }}'"
                                        >
                                        <i class="fas fa-pencil-alt"></i>
                                    </button>

                                    <button type="button"
                                        class="btn btn-outline-danger btn-sm"
                                        title="Eliminar"
                                        data-toggle="modal"
                                        data-target="#modalDelete{{ $tournament->id }}"
                                        >
                                        <i class="fas fa-trash"></i>
                                    </button>

                                    <form id="formDelete{{ $tournament->id }}"
                                        class="formDelete"
                                        method="POST"
                                        action="{{ route('tournaments.destroy', $tournament) }}"
                                        >
                                        {{ csrf_field() }}
                                        {{ method_field('DELETE') }}

                                        <modal-delete
                                            index-row-id="{{ $tournament->id }}"
                                            text-description="{{ $tournament->name }}"
                                        ></modal-delete>
                                    </form>
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
            <button id="buttonAdd" class="btn btn-dark btn-block"
                @yield('buttonAdd_disabled')
                onclick="window.location='@yield('buttonAdd_onclick')';"
            >Create</button>
        </div>
    </div>
</div>
@endsection