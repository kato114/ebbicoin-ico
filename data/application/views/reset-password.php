<!DOCTYPE html>
<html lang="en">


<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ebbicoin </title>

 
    <style>
		
/*********forget password*********/
body{
	margin:0;
	padding:0;
	font-family:sans-serif;
}
.header-nav img {
    width: 160px;
}
.forget-passwrod-page {
    padding: 70px 0;
	margin: 40px 0;
}
.forget-content {
    max-width: 550px;
    margin: auto;
    border: 1px solid #ddd;
    padding: 15px 20px;
    box-shadow: 0 2px 5px 0 rgba(0,0,0,.16), 0 2px 10px 0 rgba(0,0,0,.12);
	width:100%;
 
}
.forget-passwrod-page .card-title {
    margin: 0 0 20px;
}

.forget-passwrod-page .form-group {
    margin-bottom: 25px;
}
.header-nav {
    background-color: #000;
    padding: 10px 15px ;
}
	
    .btn.btn-warning {
    background-color: #eea43a;
    border: 1px solid #eea43a;
    padding: 10px;
    width: 100px;
    border-radius: 3px;
    margin: auto;
    display: block;
    font-size: 16px;
	color:#FFF;
}
.btn.btn-warning:hover{
	background-color: #e3992f;
    border: 1px solid #e3992f;
 
}
/* ========================================================== */
/* 02. FOOTER start */
/* ========================================================== */
 .footer {
     z-index:900;
     background-color: #000;
     padding: 15px;
     font-weight: 300;
     position: fixed;
     bottom: 0;
     width: 100%;
}
.footer p:not(.footer-logo) {
    font-size: 16px;
    color: #ffc709;
    float: none;
    line-height: 2;
    margin-left: 0;
    font-weight: 300;
    text-align: center;
    margin-top: 0;
    margin-bottom: 0;
}
 .footer p a {
     font-size: 16px;
     color: #ffc709;
     text-decoration:underline;
}

.login-block .form-control {
    width: 100%;
    border: 1px solid #ddd;
    height: 30px;
    padding: 5px;
}
.forget-passwrod-page label {
    margin-bottom: 5px;
    display: block;
    font-weight: 600;
}
.text-danger {
    color: #a94442;
}
</style>
</head>
<body>
<div id="loader" style="display: none;">
    <div class="sk-three-bounce">
        <div class="sk-child sk-bounce1"></div>
        <div class="sk-child sk-bounce2"></div>
        <div class="sk-child sk-bounce3"></div>
    </div>
</div>
<header class="header">
    <nav class="header-nav">
        <div class="container">
            <div class="">
                <a href="javascript:void(0);" class="navbar-brand brand scroll"><img src="<?=base_url()?>assets/images/logo.png" alt="EbbiCoin"></a>
            </div>
             
        </div>
    </nav>
</header>
		<!--login pop up-->
		  <div class="forget-passwrod-page" >
           		<div class="container">
                  <div class="forget-content">
                        <div class="row">
                          <div class="col-md-12 col-sm-12 ">
                            <div class="login-block">
                          
                              <form id="reset-password" method="post" action="#" novalidate>
                                <div class="card" data-background="color" data-color="blue">
                                   <div class="card-header">
                                      <h3 class="card-title" style="color: rgb(238, 164, 58);">Reset Password</h3>
                                   </div>
                                   <div class="card-content">
                                        <?php echo validation_errors('<p class="text-danger">', '</p>'); ?>
                                      <div class="form-group">
                                         <label class="control-label">
                                            Password
                                            <span>*</span>
                                         </label>
                                         <input class="form-control" name="password" placeholder="Password" style="border-radius: 0px;" type="password">
                                      </div> 
                                      
                                      <div class="form-group">
                                         <label class="control-label">
                                            Confirm Password
                                            <span>*</span>
                                         </label>
                                         <input class="form-control" name="confPassword" placeholder="Password" style="border-radius: 0px;" type="password">
                                      </div>
                                   </div>
                                   <div class="card-footer text-center">
                                      <button class="btn btn-warning" type="submit">Submit</button>
                                     
                                   </div>
                                </div>
                             </form> 
                             
                          </div>
                       </div>
                    </div>
                 </div> 
                 </div>
             
		</div> 
<div class="footer">
    
            <div class="text-center">
                <div class="copyright">
               
               
                    <p>Copyright © 2018 Ebbicoin All rights reserved.</p>
                </div>
                
                
            </div>
         
</div>

 
  
</body>


</html>