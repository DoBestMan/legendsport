@extends('backstage.users.layout')

@section('HTML-main')
@parent

<div class="container">
    <div name="titleFrm" class="row">
        <div class="col">
            <h1 class="ui-title">Users</h1>
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
                            <th scope="col">Name</th>
                            <th scope="col">E-mail</th>
                            <th scope="col">Balance</th>
                            <th scope="col" width="150px"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <th scope="row" class="text-center">{{ $user->id }}</th>
                                <td class="text-truncate">{{ $user->name }}</td>
                                <td class="text-truncate">{{ $user->email }}</td>
                                <td class="text-truncate">@{{ @json($user->balance) | formatCurrency }}</td>
                                <td class="text-right">
                                    <a
                                        class="btn btn-outline-primary btn-sm"
                                        title="View"
                                        href="{{ route('users.show', $user) }}"
                                    >
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a
                                        class="btn btn-outline-dark btn-sm"
                                        title="Edit"
                                        href="{{ route('users.edit', $user) }}"
                                    >
                                        <i class="fas fa-pencil-alt"></i>
                                    </a>

                                    <button
                                        type="button"
                                        class="btn btn-outline-danger btn-sm"
                                        title="Delete"
                                        @click='openDeleteModal(@json($user->id), @json($user->name))'
                                    >
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <hr>
            </div>
        </div>
    </div>

    <div id="buttonsFrm" class="row">
        <div class="col-3 col-lg-2">
            <a class="btn btn-dark btn-block" href="{{ route('users.create') }}">
                Create
            </a>
        </div>
    </div>
</div>

<modal-delete
    v-model="modalDeleteId"
    :text-description="modalDeleteDescription"
    @@destroy="deleteUser(modalDeleteId)"
></modal-delete>
@endsection
