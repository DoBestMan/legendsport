@php
    $isIndex = false;
    $isCreate = false;
    $isShow = false;

    $hasButtonBack = true;
    $hasButtonAdd = false;
    $hasButtonDel = true;
    $hasButtonSave = true;
@endphp

{{-- EXTEND --}}
    @extends('backstage.tournaments.layout')

{{-- VARS --}}
    @section('title', 'Show tournament')

{{-- FORM --}}
    @section('form_method', 'POST')
    @section('form_action', route('tournaments.update', $tournament))
    @section('form_laravelCsrf', csrf_field())
    @section('form_laravelMethod', method_field('PUT'))
    @section('form_disabled', 'disabled')

    @if ($tournament->avatar)
            @section('avatar_selected_true', 'selected')
        @else
            @section('avatar_selected_false', 'selected')
        @endif
        @section('avatar_class_error', error('avatar', $errors))

        @section('name_value', $tournament->name)
        @section('name_class_error', error('name', $errors))

        @if ($tournament->type == 'Single')
            @section('type_selected_single', 'selected')
        @else
            @section('type_selected_multiple', 'selected')
        @endif
        @section('type_class_error', error('type', $errors))

        @if ($tournament->prize_pool['type'] == 'Auto')
            @section('prize_pool_selected_auto', 'selected')
        @else
            @section('prize_pool_selected_fixed', 'selected')
        @endif
        @section('fixed_value', $tournament->prize_pool['fixed_value'])

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

{{-- BUTTONS --}}
    @section('buttonSave_formType', '')
    @section('buttonSave_formId', '')
