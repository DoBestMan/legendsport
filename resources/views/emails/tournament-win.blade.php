@extends('emails.email')
@section('email-content')
    <h4 style="color: #fff; font: normal 18px Arial, Helvetica, sans-serif; line-height: normal; margin: 0 0 9px 0; text-align: center;">Congratulations {{ $name }}</h4>
    <p style="color: #fff; font: normal 16px Arial, Helvetica, sans-serif; line-height: 22px; margin: 0 0 49px 0; text-align: center;">
        You finished in {{ str_ordinal($rank) }} place in {{ $tournamentName }} tournament and your real money account has been credited with
        ${{ number_format($amount / 100, 2) }}
    </p>
@endsection
