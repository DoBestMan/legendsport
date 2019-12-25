@php
    $isIndex = true;
    $isShow = false;

    $hasButtonBack = false;
    $hasButtonAdd = true;
    $hasButtonDel = false;
    $hasButtonSave = false;
@endphp

{{-- EXTEND --}}
    @extends('backstage.tournaments.layout')

{{-- VARS --}}
    @section('title', 'Tournaments')

{{-- BUTTONS --}}
    @section('buttonAdd_onclick', route('tournaments.create'))
    @section('buttonAdd_disabled', '')
