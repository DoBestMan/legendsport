@php
    $isIndex = false;

    $hasButtonBack = true;
    $hasButtonAdd = false;
    $hasButtonDel = true;
    $hasButtonSave = true;
@endphp

{{-- EXTEND --}}
    @extends('backstage.tournaments.layout')

{{-- VARS --}}
    @section('title', 'Update tournaments')

{{-- FORM --}}
    @section('form_method', 'POST')
    @section('form_action', route('tournaments.update', $tournament))
    @section('form_laravelCsrf', csrf_field())
    @section('form_laravelMethod', method_field('PUT'))
    @section('form_disabled', '')

        @if ($tournament->avatar)
            @section('avatar_selected_true', 'selected')
        @else
            @section('avatar_selected_false', 'selected')
        @endif
        @section('avatar_class_error', error('avatar', $errors))

        @section('name_value', $tournament->name)
        @section('name_class_error', error('name', $errors))

        @if ($tournament->type)
            @section('type_selected_single', 'selected')
        @else
            @section('type_selected_false', 'selected')
        @endif
        @section('type_class_error', error('type', $errors))

        @section('prize_pool_value', $tournament->prize_pool)
        @section('prize_pool_class_error', error('prize_pool', $errors))

        @section('players_limit_value', $tournament->players_limit)
        @section('players_limit_class_error', error('players_limit', $errors))

        @section('buy_in_value', $tournament->buy_in)
        @section('buy_in_class_error', error('buy_in', $errors))

        @section('commission_value', $tournament->commission)
        @section('commission_class_error', error('commission', $errors))

        @section('chips_value', $tournament->chips)
        @section('chips_class_error', error('chips', $errors))

        @if ($tournament->late_register)
            @section('late_register_selected_true', 'selected')
        @else
            @section('late_register_selected_false', 'selected')
        @endif
        @section('late_register_class_error', error('late_register', $errors))

        <!-- mejorar -->
            @if ( $tournament->state == 'Announced')
                @section('state_selected_announced', 'selected')
            @endif
            @if ( $tournament->state == 'Late registering')
                @section('state_selected_late_registering', 'selected')
            @endif
            @if ( $tournament->state == 'Running')
                @section('state_selected_running', 'selected')
            @endif
            @if ( $tournament->state == 'Complete')
                @section('state_selected_complete', 'selected')
            @endif
            @if ( $tournament->state == 'Cancel')
                @section('state_selected_cancel', 'selected')
            @endif
            @section('state_class_error', error('state', $errors))

        @section('form-loader_caption', 'ACTUALIZANDO...')

{{-- BUTTONS --}}
    @section('buttonSave_formType', 'submit')
    @section('buttonSave_formId', 'form')

    @section('HTML-formDelete')
        <form id='formDelete'
            method='POST'
            action='{{ route('tournaments.destroy', $tournament) }}'
            >
            {{ csrf_field() }}
            {{ method_field('DELETE') }}
        </form>
    @endsection

