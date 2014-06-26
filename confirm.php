<?php
//セッションを利用するのでここは削除しないで下さい
session_start();
if (SID) Err('Cookieを有効にして下さい');
if (!$_SESSION) header('Location: completion.html');

function Err($err) {
	echo <<< EOM
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>エラー：$err</title></head>
<body style="font-size: 12px; line-height: 1.8em;">
<strong>エラー : </strong>$err<br>
<input type="button" value="戻る" onclick="history.back();">
</body></html>
EOM;
	exit;
}
//ここまで
?>
<!DOCTYPE html>
<html lang="jp">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>内容確認 - ダブルメガネ株式会社</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="css/freelancer.css" rel="stylesheet" type="text/css">
    
    <!-- Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="your_website_domain/css_root/flaticon.css">

    <!-- IE8 support for HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <style>
      h1 {
        font-size: 30px;
      }
      .form-horizontal .control-label {
        padding-top: 17px;
      }
.bs-wizard {margin-top: 20px;}

/*Form Wizard*/
.bs-wizard {border-bottom: solid 1px #e0e0e0; padding: 0 0 10px 0;}
.bs-wizard > .bs-wizard-step {padding: 0; position: relative;}
.bs-wizard > .bs-wizard-step + .bs-wizard-step {}
.bs-wizard > .bs-wizard-step .bs-wizard-stepnum {color: #595959; font-size: 16px; margin-bottom: 5px;}
.bs-wizard > .bs-wizard-step .bs-wizard-info {color: #999; font-size: 14px;}
.bs-wizard > .bs-wizard-step > .bs-wizard-dot {position: absolute; width: 30px; height: 30px; display: block; background: #fbe8aa; top: 45px; left: 50%; margin-top: -15px; margin-left: -15px; border-radius: 50%;} 
.bs-wizard > .bs-wizard-step > .bs-wizard-dot:after {content: ' '; width: 14px; height: 14px; background: #fbbd19; border-radius: 50px; position: absolute; top: 8px; left: 8px; } 
.bs-wizard > .bs-wizard-step > .progress {position: relative; border-radius: 0px; height: 8px; box-shadow: none; margin: 20px 0;}
.bs-wizard > .bs-wizard-step > .progress > .progress-bar {width:0px; box-shadow: none; background: #fbe8aa;}
.bs-wizard > .bs-wizard-step.complete > .progress > .progress-bar {width:100%;}
.bs-wizard > .bs-wizard-step.active > .progress > .progress-bar {width:50%;}
.bs-wizard > .bs-wizard-step:first-child.active > .progress > .progress-bar {width:0%;}
.bs-wizard > .bs-wizard-step:last-child.active > .progress > .progress-bar {width: 100%;}
.bs-wizard > .bs-wizard-step.disabled > .bs-wizard-dot {background-color: #f5f5f5;}
.bs-wizard > .bs-wizard-step.disabled > .bs-wizard-dot:after {opacity: 0;}
.bs-wizard > .bs-wizard-step:first-child  > .progress {left: 50%; width: 50%;}
.bs-wizard > .bs-wizard-step:last-child  > .progress {width: 50%;}
.bs-wizard > .bs-wizard-step.disabled a.bs-wizard-dot{ pointer-events: none; }
/*END Form Wizard*/
    </style>
</head>

<body id="page-top" class="index">

    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-fixed-top navbar-shrink">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header page-scroll">
                <a class="navbar-brand" href="#page-top">Double Megane</a>
            </div>
        </div>
        <!-- /.container-fluid -->
    </nav>


</div>
    <section id="confirm">
      <div class="container">
        <div class="row bs-wizard" style="border-bottom:0;">
          
          <div class="col-xs-4 bs-wizard-step complete">
            <div class="text-center bs-wizard-stepnum">内容入力</div>
            <div class="progress"><div class="progress-bar"></div></div>
            <a href="#" class="bs-wizard-dot"></a>
          </div>
          
          <div class="col-xs-4 bs-wizard-step active"><!-- complete -->
            <div class="text-center bs-wizard-stepnum">確認画面</div>
            <div class="progress"><div class="progress-bar"></div></div>
            <a href="#" class="bs-wizard-dot"></a>
          </div>
          
          <div class="col-xs-4 bs-wizard-step disabled"><!-- active -->
            <div class="text-center bs-wizard-stepnum">送信完了</div>
            <div class="progress"><div class="progress-bar"></div></div>
            <a href="#" class="bs-wizard-dot"></a>
          </div>
        </div>
      </div>
<div class="container">
      <div class="row">
                <div class="col-md-8 col-md-offset-2 col-sm-12 col-sm-offset-0">
                  <h1 class="text-center">入力内容をご確認下さい</h1>
                  <form id="form" class="form-horizontal" name="form" method="post" action="sformmail.php">
                    <div class="form-group">
                      <label class="col-sm-3 control-label">お名前</label>
                      <div class="col-sm-9">
                        <p class="form-control-static"><?=$_SESSION['name']?></p>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-3 control-label">メールアドレス</label>
                      <div class="col-sm-9">
                        <p class="form-control-static"><?=$_SESSION['email']?></p>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="col-sm-3 control-label">お問い合わせ内容</label>
                      <div class="col-sm-9">
                        <p class="form-control-static"><?=$_SESSION['message']?></p>
                      </div>
                    </div>
                    <div class="col-sm-12">
                      <?php
                      //入力項目エラー判定
                      if($_SESSION['inputErr']){
                      	echo'<input type="button" class="btn btn-lg btn-default" value="戻 る" onclick="history.back()" />';
                      }else{
                      	echo'<div class="text-center">
                      	<p style="margin: 1.5em 0;">入力が正しければ、送信ボタンを押して下さい。</p>
                      <input name="mode" type="hidden" id="mode" value="SEND" />
                      <input type="submit" class="btn btn-lg btn-success" value="送 信" />
                      <input type="button" class="btn btn-lg btn-default" value="戻 る" onclick="history.back()" />
                      </div>';
                      }
                      ?>
                    </div>
                  </form>
              </div>
            </div>
</div>
    </section>

    <footer>
        <div class="footer-below text-center">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        Copyright &copy; 2014 - Double Megane, INC.
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="js/jquery-1.10.2.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <script src="js/classie.js"></script>
    <script src="js/freelancer.js"></script>
  <script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
  
    ga('create', 'UA-51728793-1', 'wmegane.com');
    ga('send', 'pageview');
    ga(‘set’, ‘&uid’, {{USER_ID}}); // ログインしている user_id を使用してUser-ID を設定します。  
  </script>

</body>

</html>

