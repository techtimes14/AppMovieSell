<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
<title>{{ $thanks['headerTitle'] }}</title>
</head>
<body>
    <table cellpadding="0" cellspacing="0" width="800" style="border:1px solid #d1d1d1; font-family: Arial, Helvetica, sans-serif; line-height: 22px; color:#8d8d8d;">
        <tr>
            <td>
                <img src="{{ asset('images/site/login_title_bg.png') }}" />
            </td>
        </tr>
        <tr>
            <td style="text-align: center;">
                <img src= "{{ asset('images/site/logo.jpg') }}"/>
            </td>
        </tr>
        <tr>
            <td style="padding:20px;">
                <p style="font-weight: bold; color:#000;">Hello {{ $userData->full_name }},</p>
                <p>Thanks to contact <strong>{{ $thanks['appname'] }}</strong></p>
                <p>Your request for being {{ $thanks['type'] }} is approved.</p>
                <p>Now you will get all the menus and facilities related to {{ $thanks['type'] }}.</p>
                
                <p style="padding-bottom: 0; margin-bottom:0; font-weight: bold; color:#000;">Thanks & Regards,</p>
                <p style="padding: 0; margin: 5px 0; color:#000;">{{ $thanks['appname'] }}</p>
            </td>
        </tr>
        <tr>
            <td><img src="{{ asset('images/site/email_templete_footer_bg.jpg') }}"  style="width:853px; display: block;"/></td>
        </tr>
        <tr>
            <td style="background: #9cc538; height: 60px; color:#fff; text-align: center; font-size: 13px;">
                    © 2019 LEEN ALKHAIR. All Rights Reserved.
            </td>
        </tr>
    </table>
</body>
</html>