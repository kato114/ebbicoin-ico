<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body style="font-family:sans-serif">
	    <div class="container" style="max-width:767px; width:100%; margin:0 auto; background-color:#f2f2f2;padding: 40px 0;">
 <table align="center"  cellpadding="0" cellspacing="0" width="100%"style="max-width:600px;border: 1px solid #eee;box-shadow: 0 2px 5px 0 rgba(0,0,0,.16), 0 2px 10px 0 rgba(0,0,0,.12);">
 		<tr  bgcolor="" style="padding:0 15px;">
        	<td align="center" style="padding:0;border-top:3px solid #c8a53e" cellpadding="0" cellspacing="0">
            <table style="font-family: sans-serif;" width="100%">	
            	<tr><td style="background-color:#000;"><img src="<?=base_url('assets/images/logo.png')?>" alt=""  style="display: block;margin: 15px auto;width: 250px;"  >
               	</td></tr> 
               </table>
            </td>
        	
        </tr>
        
        <tr  bgcolor="#fff" style="">
        	<td style="padding:0;">
                <table style="font-family: sans-serif;" width="100%">	
                
                	<tr>
                         <td width="100%" style="background-color: #000;color: #fff;padding: 0 15px; border-top: 3px solid #fff;">
                        
                        	<p style="font-size:16px;margin:4px 0;margin: 13px 0;font-family: sans-serif;">Hello , <strong style="font-size:20px;color:#c8a53e"><?=$username?></strong></p>
                            </h4>
                            
                            <p style="color:#fff;margin: 20px 0;font-size:16px ; font-family: sans-serif;">Thank you for registering with EbbiCoin. You are just one step away from completing your registration. Click on the button below to verify your account.</p>
                            <a href="<?=base_url('user/verify/'.$token)?>" style="background-color:#333; color:#c8a53e; padding:10px; border-radius:3px; box-shadow: 0 2px 5px 0 rgba(0,0,0,.16), 0 2px 10px 0 rgba(0,0,0,.12); width:100px; text-decoration:none; margin:20px 0; display:block; text-align:center; font-size:18px;">Verify</a>
                            
                            
                            <p style="color:#fff;margin: 20px 0;font-size:16px; font-family: sans-serif; ">If the button does not work, please copy the link below and paste it into your URL bar.</p>
                            <p><a href="<?=base_url('user/verify/'.$token)?>"><?=base_url('user/verify/'.$token)?></a></p>
                            
                            
                            <div style="padding-bottom:50px; margin-top:40px;">
                             <div>
                            	<p style="margin:0;">Thanks for your support,</p>
                                <h5 style="margin:0;color:#c8a53e;font-size:16px;">EbbiCoin Team</h5>
                            </div>
                            </div>
                        </td> 
                    </tr>                	
                </table>
        	</td>
        </tr> 
        
        <tr  bgcolor="" >
        	<td>
            	<table style="font-family: sans-serif; "  width="100%">
                	<tr>
                    	<td style="color:#eee ;background-color:#000;"><p style="margin:0;font-size:12px;padding:10px 0;text-align:center;">&copy; Copyright 2018 Ebisu Digital LLC All Rights Reserved</p></td>
                    </tr>
                </table>
            </td>
		</tr> 	
 </table>

</body>
</html>
