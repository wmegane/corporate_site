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
<!doctype html>
<html>
  <head>
    <meta charset='utf-8'>
      <title>お問い合わせ - ダブルメガネ株式会社</title>
      <meta content='width=device-width, initial-scale=1.0' name='viewport'>
      <meta content='' name='description'>
      <meta content='http://bootstraptaste.com' name='author'>
      <link href='/images/favicon.ico' rel='shortcut icon'>
      <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
      <!--[if lt IE 9]>
        <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
      <![endif]-->
    </meta>

    <link href='//fonts.googleapis.com/css?family=Lato:300,400' rel='stylesheet' type='text/css'>
    <link href="/assets/stylesheets/all.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/b656bac87e.js"></script>
    <script src="/assets/javascripts/jquery.js"></script>
    <script src="/assets/javascripts/jquery.easing.1.3.js"></script>
    <script src="/assets/javascripts/bootstrap.min.js"></script>
    <script src="/assets/javascripts/jquery.fancybox.pack.js"></script>
    <script src="/assets/javascripts/jquery.fancybox-media.js"></script>
    <script src="/assets/javascripts/prettify.js"></script>
    <script src="/assets/javascripts/jquery.quicksand.js"></script>
    <script src="/assets/javascripts/setting.js"></script>
    <script src="/assets/javascripts/jquery.flexslider.js"></script>
    <script src="/assets/javascripts/animate.js"></script>
    <script>
      var polyfilter_scriptpath = '/';
    </script>
    <script src="/assets/javascripts/contentloaded.js"></script>
    <script src="/assets/javascripts/cssParser.js"></script>
    <script src="/assets/javascripts/css-filters-polyfill.js"></script>
    <script src="/assets/javascripts/custom.js"></script>
  </head>

  <body>
    <?php include_once("analyticstracking.php") ?>
    <div id='wrapper'>
      <header>
        <div class='navbar navbar-default navbar-static-top'>
          <div class='container'>
            <div class='navbar-header'>
              <button class='navbar-toggle' data-target='.navbar-collapse' data-toggle='collapse' type='button'>
                <span class='icon-bar'></span>
                <span class='icon-bar'></span>
                <span class='icon-bar'></span>
              </button>
              <a class='navbar-brand' href='/'><span>D</span>ouble megane
              </a>
            </div>
            <div class='navbar-collapse collapse'>
              <ul class='nav navbar-nav'>
                <li>
                  <a href='/about/'>会社情報</a>
                </li>
                <li>
                  <a href='/works/'>開発実績</a>
                </li>
                <li>
                  <a href='/recruit/'>採用情報</a>
                </li>
                <li class='active'>
                  <a href='/contact/'>お問い合わせ</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </header>
      <section id='inner-headline'>
        <div class='container'>
          <div class='row'>
            <div class='col-lg-12'>
              <ul class='breadcrumb'>
                <li>
                  <a href='/'>
                    <i class='fa fa-home'></i>
                  </a>
                  <i class='icon-angle-right'></i>
                </li>
                <li class='active'>お問い合わせ</li>
              </ul>
            </div>
          </div>
        </div>
      </section>
      <section id='content'>
        <div class='container'>
          <div class='row'>
                <div class="col-lg-10 col-lg-offset-1">
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
        <div class='container'>
          <div class='row'>
            <div class='col-lg-12 text-center'>
              <div class='widget'>
                <h3 class='widgetheading'>会社情報</h3>
                <address>
                  <strong>ダブルメガネ株式会社</strong>
                  <br>
                    〒180-0023
                    <br>
                      東京都武蔵野市境南町３丁目６−１０
                    </br>
                  </br>
                </address>
              </div>
            </div>
          </div>
        </div>
        <div id='sub-footer'>
          <div class='container'>
            <div class='row'>
              <div class='col-lg-6'>
                <div class='copyright'>
                  <p>
                    <span>&copy; Double Megane 2014 - 2015 All right reserved.</span>
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </footer>
    </div>
    <a class='scrollup' href='../#'>
      <i class='fa fa-angle-up active'></i>
    </a>
  </body>
</html>
