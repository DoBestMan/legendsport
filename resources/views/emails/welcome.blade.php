@section('email-content')
    <h4 style="color: #fff; font: normal 18px Arial, Helvetica, sans-serif; line-height: normal; margin: 0 0 9px 0; text-align: center;">Dear {{ $name }}</h4>
    <p style="color: #fff; font: normal 16px Arial, Helvetica, sans-serif; line-height: 22px; margin: 0 0 49px 0; text-align: center;">
        Welcome to your LegendsBet account. Please click the<br />
        button below to verify your email address and gain full <br />access to your account.</p>
    <a href="#" style="background: #efbb01; border-radius: 5px; color: #564509; display:block; font: normal 12px Arial, Helvetica, sans-serif; line-height: 53px; margin: 0 0 34px 0; text-align: center; text-decoration: none;">Verify your Email</a>
    <h5 style="color: #9198a2; font: normal 12px Arial, Helvetica, sans-serif; margin: 0 0 50px 0; text-align: center;">For your security, <a href="#" style="color: #efbb01;">this link</a> will remain accessible for only 24 hours. </h5>
@endsection
