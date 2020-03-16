{{-- EXTEND --}}
    @extends('backstage.config.layout')

{{-- VARS --}}
    @section('title', 'Configuration')

    @section('HTML-main')
        <fieldset disabled>
            @parent
        </fieldset>

        <div class="container">
            <a class="btn btn-primary" href="{{ route('config.edit') }}">
                Edit
            </a>
        </div>
    @endsection
