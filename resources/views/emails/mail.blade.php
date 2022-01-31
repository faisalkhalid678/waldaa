<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>Part Time App</title>
    <style>a {
            text-decoration: none;
            cursor: pointer;
        }</style>
</head>
<body style="margin:0px; background: #f8f8f8; ">
<div width="100%"
     style="background: #f8f8f8; padding: 0px 0px; font-family:arial; line-height:28px; height:100%;  width: 100%; color: #514d6a;">
    <div style="max-width: 700px; padding:50px 0;  margin: 0px auto; font-size: 14px">
        <table border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin-bottom: 20px">
            <tbody>
            <tr>
                <td style="vertical-align: top; padding-bottom:0px;" align="center"><a href="javascript:"
                                                                                       target="_blank">
                      <!--  <img src="<?php echo URL::to('/public/logo/logo.png'); ?>"style="border:none;width:300px;height:auto;"> -->
                      </a></td>
            </tr>
            </tbody>
        </table>
        <div style="padding: 40px; background: #fff;">
            <table border="0" cellpadding="0" cellspacing="0" style="width: 100%;">
                <tbody>
                <tr>
                    <td><b>Hi, <?php echo ucfirst($name); ?></b>
                        <p>Use The following Verification code to reset your password.</p>
                        <h2><?php echo $verification_code; ?></h2>
                        <p></p>
                        <b>- Thanks (Waldaa App Team)</b></td>
                </tr>
                </tbody>
            </table>
        </div>
        <div style="text-align: center; font-size: 12px; color: #b2b2b5; margin-top: 20px">
            <p> Powered by <a href="javascript:" style="color: #b2b2b5; text-decoration: underline;cursor: pointer;">Waldaa App</a></p>
        </div>
    </div>
</div>
</body>
</html>