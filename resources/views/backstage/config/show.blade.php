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

    @section('chips_value', $config->config['chips'])

    @section('commission_value', $config->config['commission'])

    @section('keep_completed_value', $config->config['keep_completed'])

    @section('HTML-btnAction')
        <a class="btn btn-primary"
            href="{{ route('config.edit') }}"
            role="button"
        >Editar</a>
    @endsection