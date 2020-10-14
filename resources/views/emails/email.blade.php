<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>LegendsBet</title>
</head>

<body bgcolor="#FFFFFF" style="margin: 0; padding: 0;">
<table style="margin:0 auto; background-color:#000000;" border="0" cellpadding="0" cellspacing="0" width="600">
    <tbody>
    <tr>
        <td><img src="{{ $message->embed(resource_path() . '/views/emails/images/image1.jpg') }}" width="600" height="345" border="0" alt="" /></td>
    </tr>
    <tr>
        <td style="border-bottom: 1px solid #1c1d1f; padding: 0 30px 0 30px;">
            @yield('email-content')
        </td>
    </tr>
    <tr>
        <td style="border-bottom: 1px solid #1c1d1f; padding: 38px 0 0 0;">
            <h3 style="color: #fff; font: normal 14px Arial, Helvetica, sans-serif; line-height: 22px; margin: 0 0 34px 0; text-align: center;">
                Questions about LegendsBet? Please email our support staff at <br />
                <a href="mailto:info@legendsbet.com" style="color: #efbb01; text-decoration: none;">info@legendsbet.com</a>
            </h3>
            <h4 style="color: #545456; font: normal 13px Arial, Helvetica, sans-serif; line-height: 22px; margin: 0 0 30px 0; text-align: center;">
                <a href="#" style="display: inline-block; margin: 0 8px 10px 8px; text-decoration: none;">
                    <img src="{{ $message->embed(resource_path() . '/views/emails/images/fb-icon-img.png') }}" alt="" border="0" />
                </a>
                <a href="#" style="display: inline-block; margin: 0 8px 10px 8px; text-decoration: none;">
                    <img src="{{ $message->embed(resource_path() . '/views/emails/images/twtr-icon-img.png') }}" alt="" border="0" />
                </a>
                <a href="#" style="display: inline-block; margin: 0 8px 10px 8px; text-decoration: none;">
                    <img src="{{ $message->embed(resource_path() . '/views/emails/images/instagram-icon-img.png') }}" alt="" border="0" />
                </a>
                <a href="#" style="display: inline-block; margin: 0 8px 10px 8px; text-decoration: none;">
                    <img src="{{ $message->embed(resource_path() . '/views/emails/images/lnkdinr-icon-img.png') }}" alt="" border="0" />
                </a>
                <br />This email was sent to you by LegendsBet.
            </h4>
            <h5 style="color: #ffffff; font:normal 13px Arial, Helvetica, sans-serif; line-height: 22px; margin: 0 0 37px 0; text-align: center;">
                16, Satya Vijay Shopping Cntr, <br />Goddev Naka, Bhayandere
            </h5>
        </td>
    </tr>
    <tr>
        <td style="color: #545456; font: normal 11px Arial, Helvetica, sans-serif; margin: 0 0 0 0; padding: 26px 0 26px 0; text-align: center;">
            <a href="#" style="color: #545456; display: inline-block; text-decoration: none;">Privacy Policy</a> |
            <a href="#" style="color: #545456; display: inline-block; text-decoration: none;">Terms &amp; Conditions</a>
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>
