@section('email-content')
    <h4 style="color: #fff; font: normal 18px Arial, Helvetica, sans-serif; line-height: normal; margin: 0 0 9px 0; text-align: center;">Dear {{ $name }}</h4>
    <p style="color: #fff; font: normal 16px Arial, Helvetica, sans-serif; line-height: 22px; margin: 0 0 49px 0; text-align: center;">
        We have now processed your request to withdraw ${{ number_format($data['amount'] / 100, 2) }} to your bitcoin wallet {{ $data['btcAddress'] }}
    </p>
@endsection
