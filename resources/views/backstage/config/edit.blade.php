{{-- EXTEND --}}
    @extends('backstage.config.layout')

{{-- VARS --}}
    @section('title', 'Configuration')

{{-- FORM --}}
    @section('form_method', 'POST')
    @section('form_action', route('config.update'))
    @section('form_laravelCsrf', csrf_field())
    @section('form_laravelMethod', method_field('PUT'))
    @section('form_disabled', '')

    @section('HTML-btnAction')
        <button class="btn btn-success"
            type="submit"
            form="form"
            @click="isValidForm()"
        >Save</button>
    @endsection

    @section('chips_value', $config->config['chips'])

    @section('commission_value', $config->config['commission'])

    @section('keep_completed_value', $config->config['keep_completed'])
