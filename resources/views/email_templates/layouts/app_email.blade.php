<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>{{$siteSetting->website_title}}</title>
  <style type="text/css">
  p{ margin:0; padding:12px 0 0 0; line-height:22px;}
  </style>
</head>

<body style="background:#efefef; margin:0; padding:0;">
  <table width="600" border="0" align="center" cellpadding="0" cellspacing="0" style="font-family:Arial, Helvetica, sans-serif; font-size:14px;">
    <tbody>
      <tr>
        <td align="center" valign="middle" bgcolor="#eef1f3" style="padding:15px; margin:0; line-height:0; border-top:1px solid #eef1f3; border-bottom:1px solid #eef1f3; border-left:1px solid #eef1f3; border-right:1px solid #eef1f3;"><a target="_blank" href="{{route('site.home')}}"><img src="{{asset('images/site/logo.png')}}" alt="" style="border:0;" width="182" height="52" /></a></td>
      </tr>
      <tr>
        <td align="left" valign="top" bgcolor="#ffffff" style="color:#3c3c3c; margin:0; padding:15px 15px 30px 15px; border-left: 1px solid #eef1f3; border-right: 1px solid #eef1f3;">
          @yield('content')
        </td>
      </tr>
      <tr>
        <td align="center" valign="middle" bgcolor="#262b30" style="padding:20px; color:#ffffff; margin:0; line-height:0;">
          <table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td width="70%" align="left" valign="middle">
                <a style="color:#fff; text-decoration:none;" target="_blank">Terms & Condition</a> <a style="color:#fff;">|</a> <a style="color:#fff; text-decoration:none;" target="_blank">Privacy Policy</a>
              </td>
              <td width="30%" align="right" valign="middle">
                <a href="{{$siteSetting->instagram_link}}" target="_blank"><img src="{{asset('images/site/instagram.png')}}" alt="" style="border:0;" /></a> <a href="{{$siteSetting->facebook_link}}" target="_blank"><img src="{{asset('images/site/facebook.png')}}" alt="" style="border:0;" /></a> <a href="{{$siteSetting->twitter_link}}" target="_blank"><img src="{{asset('images/site/twitter.png')}}" alt="" style="border:0;" /></a> <a href="{{$siteSetting->googleplus_link}}" target="_blank"><img src="{{asset('images/site/g-plus.png')}}" alt="" style="border:0;" /></a>
                {{-- <a href="{{$siteSetting->linkedin_link}}" target="_blank"><img src="images/linkedin.png" alt="" style="border:0;" /></a> <a href="{{$siteSetting->youtube_link}}" target="_blank"><img src="{{asset('images/site/youtube.png')}}" alt="" style="border:0;" /></a>--}}
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td align="center" valign="middle" bgcolor="#192027" style="padding:20px; color:#ffffff; margin:0; line-height:0;">©Copyright {{date('Y')}}. All Right Reserve.</td>
      </tr>
    </tbody>
  </table>
</body>
</html>
