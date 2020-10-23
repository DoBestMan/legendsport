@extends('emails.email')
@section('email-content')
    <h4 style="color: #fff; font: normal 18px Arial, Helvetica, sans-serif; line-height: normal; margin: 0 0 9px 0; text-align: center;">Congratulations {{ $name }}</h4>
    <p style="color: #fff; font: normal 16px Arial, Helvetica, sans-serif; line-height: 22px; margin: 0 0 49px 0; text-align: center;">

        You have placed in the money in the {{ $tournamentName }} tournament your balance has been credited with your {{ str_ordinal($rank) }} place
        winnings of ${{ number_format($amount, 2) }}

        Legend Sports
    </p>
@endsection
