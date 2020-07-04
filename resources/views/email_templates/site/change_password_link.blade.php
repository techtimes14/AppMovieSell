@extends('email_templates.layouts.app_email')
  @section('content')
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td style="color:#141414; font-size:15px;">  Hello {{ $user->full_name }},</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td> Please click below button to change your password.</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>
          <table width="100%" border="0" cellspacing="0" cellpadding="5">
            <tr>              
              <td width="78%" align="left" valign="top" style="line-height:20px;"><a href="{{ $app_config['appLink'].'/'.$app_config['controllerName'].'/reset-password/'.$user->remember_token }}" style="padding: 10px;font-size: 15px;background-color: #0674ec;color: #FFF;border: none;border-radius: 5px;text-decoration: none;">Click Here</a></td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td style="color:#141414; font-size:15px; line-height: 20px;">
          Thanks & Regards,<br>
          {{$siteSetting->website_title}}
        </td>
      </tr>
    </table>
    
  @endsection