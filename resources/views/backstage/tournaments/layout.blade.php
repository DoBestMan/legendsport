{{-- EXTEND --}}
    @extends('backstage.layout')

{{-- VARS --}}

{{-- CSS --}}
    @section('HTML-cssVendors')

    @endsection

    @section('HTML-css')
        <link rel="stylesheet" href="{{ asset('backstage/css/tournaments.css') }}">
    @endsection

{{-- JS --}}
    @section('HTML-jsVendors')

    @endsection

    @section('HTML-js')
        <script type="text/javascript" language="javascript" src="{{ asset('backstage/js/tournaments.js') }}"></script>
    @endsection

{{-- HTML --}}
@section('HTML-main')
    <div class="container">
        <div id="titleFrm" class="row">
            <div class="col">
                <h1>@yield('title')</h1>
            </div>
        </div>

        @if ($isIndex == true)
            <div id="tableFrm" class="row">
                <div class="col">
                    <div class="table-responsive">
                        <table class="table table-sm table-light table-striped table-borderless table-hover">
                            <caption>tournaments list</caption>

                            <thead class="thead-dark">
                                <tr>
                                    <th scope="col" class="text-center" width="25px">#</th>
                                    <th scope="col" width="160px">Name</th>
                                    <th scope="col" width="180px">Type</th>
                                    <th scope="col" width="140px">Prize pool</th>
                                    <th scope="col" width="130px">Players limit</th>
                                    <th scope="col" width="100px">Commission</th>
                                    <th scope="col" width="200px">State</th>
                                    <th scope="col" width="150px"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tournaments as $tournament)
                                    <tr>
                                        <th scope="row" class="text-center">{{ $numFirstItemPage++ }}</th>
                                        <td class="text-truncate">{{ $tournament->name }}</td>
                                        <td class="text-truncate">{{ $tournament->type }}</td>
                                        <td class="text-truncate">{{ $tournament->prize_pool }}</td>
                                        <td class="text-truncate">{{ $tournament->players_limit }}</td>
                                        <td class="text-truncate">{{ $tournament->commission }}</td>
                                        <td class="text-truncate">{{ $tournament->state }}</td>
                                        <td class="text-right">
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
                <div class="col-3 col-lg-1">
                    <button id="buttonAdd" class="btn btn-dark btn-block"
                        @yield('buttonAdd_disabled')
                        onclick="window.location='@yield('buttonAdd_onclick')';"
                    >Crear</button>
                </div>
            </div>
        @else
            <div id="formsFrm">
                <form id="form"
                    method="@yield("form_method")"
                    action="@yield("form_action")"
                    >
                    @yield("form_laravelCsrf")
                    @yield("form_laravelMethod")

                    <div class="form-row">
                        <div class="col-12 col-xl-6">
                            <div id="avatarFrm" class="form-row form-group">
                                <div class="col-12 col-lg-3 text-right">
                                    <label for="avatar" class="col-form-label">avatar</label>
                                </div>

                                <div class="col-12 col-lg-2">
                                    <select name="avatar"
                                        id="avatar"
                                        class="form-control @yield('avatar_class_error')"
                                        @yield('form_disabled')
                                        >
                                        <option></option>
                                        <option value=1 @yield('valid_selected_true')>yes</option>
                                        <option value=0 @yield('valid_selected_false')>no</option>

                                    </select>

                                    @error('avatar')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div id="nameFrm" class="form-row form-group">
                                <div class="col-12 col-lg-3 text-right">
                                    <label for="name" class="col-form-label">Name</label>
                                </div>

                                <div class="col-12 col-lg-7">
                                    <input type="text"
                                        id="name"
                                        name="name"
                                        class="form-control @yield('name_class_error')"
                                        value="@yield('name_value')"
                                        @yield('form_disabled')
                                        required
                                    >

                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div id="typeFrm" class="form-row form-group">
                                <div class="col-12 col-lg-3 text-right">
                                    <label for="type" class="col-form-label">type</label>
                                </div>

                                <div class="col-12 col-lg-3">
                                    <select name="type"
                                        id="type"
                                        class="form-control @yield('type_class_error')"
                                        @yield('form_disabled')
                                        >
                                        <option></option>

                                        <option value="single" @yield('valid_selected_true')>single</option>
                                        <option value="multiple" @yield('valid_selected_false')>multiple</option>
                                    </select>

                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div id="prize_poolFrm" class="form-row form-group">
                                <div class="col-12 col-lg-3 text-right">
                                    <label for="prize_pool" class="col-form-label">prize pool</label>
                                </div>

                                <div class="col-12 col-lg-3">
                                    <input type="text"
                                        name="prize_pool"
                                        id="prize_pool"
                                        class="form-control @yield('prize_pool_class_error')"
                                        value="@yield('prize_pool_value')"
                                        placeholder=""
                                        @yield('form_disabled')
                                    >

                                    @error('prize_pool')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div id="players_limitFrm" class="form-row form-group">
                                <div class="col-12 col-lg-3 text-right">
                                    <label for="players_limit" class="col-form-label">players limit</label>
                                </div>

                                <div class="col-12 col-lg-2">
                                    <input type="text"
                                        name="players_limit"
                                        id="players_limit"
                                        class="form-control @yield('players_limit_class_error')"
                                        value="@yield('players_limit_value')"
                                        placeholder=""
                                        @yield('form_disabled')
                                    >

                                    @error('players_limit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-xl-6">
                            <div id="buy_inFrm" class="form-row form-group">
                                <div class="col-12 col-lg-3 text-right">
                                    <label for="buy_in" class="col-form-label">buy in</label>
                                </div>

                                <div class="col-12 col-lg-3">
                                    <input type="text"
                                        name="buy_in"
                                        id="buy_in"
                                        class="form-control @yield('buy_in_class_error')"
                                        value="@yield('buy_in_value')"
                                        placeholder=""
                                        @yield('form_disabled')
                                    >

                                    @error('buy_in')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div id="chipsFrm" class="form-row form-group">
                                <div class="col-12 col-lg-3 text-right">
                                    <label for="chips" class="col-form-label">chips</label>
                                </div>

                                <div class="col-12 col-lg-3">
                                    <input type="text"
                                        name="chips"
                                        id="chips"
                                        class="form-control @yield('chips_class_error')"
                                        value="@yield('chips_value')"
                                        placeholder=""
                                        @yield('form_disabled')
                                    >

                                    @error('chips')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div id="commissionFrm" class="form-row form-group">
                                <div class="col-12 col-lg-3 text-right">
                                    <label for="commission" class="col-form-label">commission</label>
                                </div>

                                <div class="col-12 col-lg-2">
                                    <input type="text"
                                        name="commission"
                                        id="commission"
                                        class="form-control @yield('commission_class_error')"
                                        value="@yield('commission_value')"
                                        placeholder=""
                                        @yield('form_disabled')
                                    >

                                    @error('commission')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div id="late_registerFrm" class="form-row form-group">
                                <div class="col-12 col-lg-3 text-right">
                                    <label for="late_register" class="col-form-label">late register</label>
                                </div>

                                <div class="col-12 col-lg-2">
                                    <select name="late_register"
                                        id="late_register"
                                        class="form-control @yield('late_register_class_error')"
                                        v-model="lateRegister"
                                        @yield('form_disabled')
                                        >
                                        <option></option>
                                        <option value=1 @yield('valid_selected_true')>yes</option>
                                        <option value=0 @yield('valid_selected_false')>no</option>

                                    </select>

                                    @error('late_register')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <template v-if="lateRegister == 1">
                                <div id="intervalFrm" class="form-row form-group">
                                    <div class="col-12 col-lg-3 text-right">
                                        <label for="interval" class="col-form-label">interval</label>
                                    </div>

                                    <div class="col-12 col-lg-2">
                                        <input type="text"
                                            name="late_register_rule[]"
                                            id="interval"
                                            class="form-control @yield('interval_class_error')"
                                            value="@yield('interval_value')"
                                            placeholder=""
                                            @yield('form_disabled')
                                        >

                                        @error('interval')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div id="valueFrm" class="form-row form-group">
                                    <div class="col-12 col-lg-3 text-right">
                                        <label for="value" class="col-form-label">value</label>
                                    </div>

                                    <div class="col-12 col-lg-2">
                                        <input type="text"
                                            name="late_register_rule[]"
                                            id="value"
                                            class="form-control @yield('value_class_error')"
                                            value="@yield('value_value')"
                                            placeholder=""
                                            @yield('form_disabled')
                                        >

                                        @error('value')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </template>

                            <div id="stateFrm" class="form-row form-group">
                                <div class="col-12 col-lg-3 text-right">
                                    <label for="state" class="col-form-label">state</label>
                                </div>

                                <div class="col-12 col-lg-4">
                                    <select name="state"
                                        id="state"
                                        class="form-control @yield('state_class_error')"
                                        @yield('form_disabled')
                                        >
                                        <option></option>
                                        <option value="announced">Announced</option>
                                        <option value="registering">Registering</option>
                                        <option value="late registering">Late registering</option>
                                        <option value="running">Running</option>
                                        <option value="complete">Complete</option>
                                        <option value="cancel">cancel</option>

                                    </select>

                                    @error('state')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row form-group">
                                <div class="col-12 col-lg-3 text-right">
                                    <label for="state" class="col-form-label">Prize</label>
                                </div>

                                <div class="col-12 col-lg-2">
                                    <input type="number"
                                        class="form-control"
                                        v-model.number="inputLimit"
                                        @yield('form_disabled')
                                    >
                                </div> 
                            </div>

                            <template v-for="(inputExit, index) in inputLimit">
                                <div class="form-row form-group">
                                    <div class="col-12 col-lg-3 text-right">
                                        <label for="state" class="col-form-label">Prize @{{ index + 1 }}</label>
                                    </div>

                                    <div class="col-12 col-lg-2">
                                        <input type="number"
                                            name="prizes[]"
                                            class="form-control"
                                            @yield('form_disabled')
                                        >

                                        @error('prizes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div> 
                                </div>
                            </template>
                        </div>
                    </div>

                    <hr>

                    <!-- <form-loader :sending="formIsSending" caption="@yield('form-loader_caption')"></form-loader> -->
                </form>

                @yield('HTML-formDelete')
            </div>

            <div id="buttonsFrm" class="form-row form-group">
                <div class="col-1">
                    @if ($hasButtonBack == true)
                        <button id="buttonBack" class="btn btn-light btn-link"
                            onclick="window.location='{{ route('tournaments.index') }}';"
                        >Regresar</button>
                    @endif
                </div>

                <div class="offset-2 offset-lg-1 col-1">
                    @if ($hasButtonSave == true)
                        <button class="btn btn-dark"
                            type="@yield('buttonSave_formType')"
                            form="@yield('buttonSave_formId')"
                            @yield('buttonSave_disabled')
                            @click="isValidForm()"
                        >Guardar</button>
                    @endif
                </div>

                <div class="offset-5 col-1 offset-lg-8 col-lg-1">
                    @if ($hasButtonDel == true)
                        <button class="btn btn-danger"
                            type="button"
                            data-toggle="modal"
                            data-target="#modalDelete"
                            @yield('form_disabled')
                        >Delete</button>

                        <modal-delete
                            text-description="{{ $tournament->name }}"
                        ></modal-delete>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endsection
