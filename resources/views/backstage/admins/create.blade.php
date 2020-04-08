@extends('backstage.admins.layout')

@section('HTML-main')
    @parent

    <div class="container">
        <div class="row">
            <div class="col">
                <h1 class="ui-title">Create admin</h1>
            </div>
        </div>

        <hr/>

        <form @submit.prevent="createAdmin">
            <admin-form
                :name.sync="name"
                :password.sync="password"
                :errors="errors"
            ></admin-form>

            <hr/>

            <div id="buttonsFrm" class="form-row form-group">
                <div class="col-2">
                    <a class="btn btn-light btn-link" href="{{ route('admins.index') }}">
                        Return
                    </a>
                </div>

                <div class="offset-2 offset-lg-1 col-2">
                    <action-button class="btn-dark">
                        Save
                    </action-button>
                </div>
            </div>
        </form>
    </div>
@endsection
