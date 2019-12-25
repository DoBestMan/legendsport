{{-- EXTEND --}}
    @extends('backstage.config.layout')

{{-- VARS --}}
    @section('title', 'Show config')

{{-- FORM --}}
    @section('form_method', '')
    @section('form_action', '')
    @section('form_laravelCsrf', '')
    @section('form_laravelMethod', '')
    @section('form_disabled', 'disabled')

    @section('HTML-btnAction')
        <a class="btn btn-primary"
            href="{{ route('config.edit') }}"
            role="button"
        >Editar</a>
    @endsection

    @section('chips_value', $config->chips)

    @section('commission_value', $config->commission)
