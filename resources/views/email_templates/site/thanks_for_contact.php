@extends('email_templates.layouts.app_email')
  @section('content')
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td style="color:#141414; font-size:15px;"> Hello Administrator,</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td>{{$newContact->full_name}} contact with you</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      
      <tr>
        <td>
          <table width="100%" border="0" cellspacing="0" cellpadding="5">
            <tr>
              <td width="25%" align="left" valign="top" style="color:#141414; font-weight:bold; line-height:20px;">First Name</td>
              <td width="2%" align="left" valign="top" style="line-height:20px;">:</td>
              <td width="73%" align="left" valign="top" style="line-height:20px;">{{$newContact['first_name']}}</td>
            </tr>
            <tr>
              <td width="25%" align="left" valign="top" style="color:#141414; font-weight:bold; line-height:20px;">Last Name</td>
              <td width="2%" align="left" valign="top" style="line-height:20px;">:</td>
              <td width="73%" align="left" valign="top" style="line-height:20px;">{{$newContact['last_name']}}</td>
            </tr>
            <tr>
              <td width="25%" align="left" valign="top" style="color:#141414; font-weight:bold; line-height:20px;">Email</td>
              <td width="2%" align="left" valign="top" style="line-height:20px;">:</td>
              <td width="73%" align="left" valign="top" style="line-height:20px;">{{$newContact['email']}}</td>
            </tr>
            <tr>
              <td width="25%" align="left" valign="top" style="color:#141414; font-weight:bold; line-height:20px;">Phone Number</td>
              <td width="2%" align="left" valign="top" style="line-height:20px;">:</td>
              <td width="73%" align="left" valign="top" style="line-height:20px;">{{$newContact['phone_number']}}</td>
            </tr>
            <tr>
              <td width="25%" align="left" valign="top" style="color:#141414; font-weight:bold; line-height:20px;">Subject</td>
              <td width="2%" align="left" valign="top" style="line-height:20px;">:</td>
              <td width="73%" align="left" valign="top" style="line-height:20px;">{{$newContact['subject']}}</td>
            </tr>
          </table>
        </td>
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