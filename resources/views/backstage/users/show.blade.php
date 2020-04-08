@extends('backstage.users.layout')

@section('HTML-main')
@parent

<div class="container">
    <div class="row">
        <div class="col">
            <h1 class="ui-title">Show user</h1>
        </div>
    </div>

    <hr>

    <fieldset disabled>
        <user-form
            :name="name"
            :email="email"
            :balance="balance"
            :errors="errors"
        ></user-form>
    </fieldset>

    <hr/>

    <div id="buttonsFrm" class="form-row form-group">
        <div class="col-1">
            <a class="btn btn-light btn-link" href="{{ route('users.index') }}">
                Return
            </a>
        </div>
    </div>
</div>
@endsection
