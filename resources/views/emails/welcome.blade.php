@extends('emails.email')
@section('email-content')
    <h4 style="color: #fff; font: normal 18px Arial, Helvetica, sans-serif; line-height: normal; margin: 0 0 9px 0; text-align: center;">Dear {{ $name }}</h4>
    <p style="color: #fff; font: normal 16px Arial, Helvetica, sans-serif; line-height: 22px; margin: 0 0 49px 0; text-align: center;">
        Welcome to your LegendsBet account. Win cash prizes!<br />
        Jump right in and experience an all new tournament style <br />
        format which will keep you on the edge of your seat. <br />
        Do you have what it takes to be a legend? </p>
    <a href="{{ url('/', [], true) }}" style="background: #efbb01; border-radius: 5px; color: #564509; display:block; font: normal 12px Arial, Helvetica, sans-serif; line-height: 53px; margin: 0 0 34px 0; text-align: center; text-decoration: none;">Join a tournament</a>
@endsection
