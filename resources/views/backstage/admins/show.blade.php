@extends('backstage.admins.layout')

@section('HTML-main')
@parent

<div class="container">
    <div class="row">
        <div class="col">
            <h1 class="ui-title">Show admin</h1>
        </div>
    </div>

    <hr>

    <fieldset disabled>
        <admin-form
            :name="name"
            :errors="errors"
        ></admin-form>
    </fieldset>

    <hr/>

    <div id="buttonsFrm" class="form-row form-group">
        <div class="col-1">
            <a class="btn btn-light btn-link" href="{{ route('admins.index') }}">
                Return
            </a>
        </div>
    </div>
</div>
@endsection
