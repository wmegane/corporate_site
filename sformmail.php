<?php

/*--------------------------------------------------------------
	フォームメール - sformmmail
	2008-8-20 Ver. 1.40
	(c)sapphirus.biz
	
	詳しい説明は下記のURLを参照して下さい。
	http://www.sapphirus.biz/php/sformmail/
	
	sformmail.php - 本体
	sformmail.html - 入力フォーム
	sformmail.css - 共通スタイルシート
	confirm.php - 確認画面用
	completion.html - 送信完了画面
	template.php - メール送信用テンプレート
	reply.php - 自動返信用テンプレート
	
	フォームのnameに「;s」オプションをつけると
	必須項目扱いになります。
	例) name="comment;s"
	nameにemailを指定するとメールアドレスとして扱われます。
	nameにemailcheckを指定するとメールアドレスの再入力の確認を
	することができます。
	※emailを使わない場合、emailcheckも利用しないようにして下さい。
	
	入力画面もしくは確認画面で
	「autoReply」に対して「1」を渡すと入力されたメールアドレスに
	自動返信をします。
	例）<input name="autoReply" type="hidden" value="1" />
	or　<input name="autoReply" type="checkbox" value="1" />
	※emailの項目がない場合は無効になります。
	
	確認画面用(confirm.php)には非表示フィールドで
	「mode」に対して「SEND」を必ず渡して下さい。
	例）<input name="mode" type="hidden" value="SEND" />
================================================================
	画面の流れ
	sformmail.html(入力) ≫ sformmail.php(入力チェック) ≫
	confirm.php(確認) ≫ sformmail.php(送信[template.php/reply.php]) ≫
	completion.html(完了)
--------------------------------------------------------------*/

// 設定
$mail_to = 'contact@wmegane.com'; // フォームデータを受け取るメールアドレス
$mail_subject = 'ダブルメガネ株式会社 フォームメール送信'; // 受け取る時のSubject（件名）
$reply_subject = 'ダブルメガネ株式会社 お問い合わせより自動返信です'; // 送信者へ自動返信のSubject（件名）
$mail_bcc = ''; // BCCで受け取りが必要な場合は設定
$internal_enc = 'UTF-8'; // 文字エンコード


// メイン
session_start();
if (!extension_loaded('mbstring')) Err('マルチバイト文字列関数が利用できません');
if (!$mail_to) Err('受取先メールアドレスが設定されてません');
if (!$_POST) Err('POSTデータがありません');
mb_language('ja');
mb_internal_encoding($internal_enc);
$x_mailer = 'Sapphirus.Biz Formmail Ver. 1.40 (PHP/' . phpversion() . ')';
$mode = $_POST['mode'];

switch ($mode) {
case 'SEND': // メール送信
	if (!$_SESSION) Err('セッションデータがありません');

	// メールヘッダ
	if (!$_SESSION['email']) $mail_from = 'S.B.Formmail';
	else $mail_from = $_SESSION['email'];
	$mail_header  = "From: {$mail_from}\n";
	if ($mail_bcc) $mail_header .= "Bcc: {$mail_bcc}\n";
	$mail_header .= "X-Mailer: {$x_mailer}";

	// メール送信
	include ('template.php');
	$mail_message = html_entity_decode($mail_message, ENT_QUOTES, $internal_enc);
	$mail_message = str_replace("<br />", "", $mail_message);
	$mail_message = str_replace("\t", "\n", $mail_message);
	$mail_message = mb_convert_encoding($mail_message, $internal_enc, 'AUTO');
	mb_send_mail($mail_to, $mail_subject, $mail_message, $mail_header);

	// メール自動返信
	if ($_SESSION['autoReply'] && $_SESSION['email'] && is_file('reply.php')) {
		$reply_header  = "From: {$mail_to}\n";
		if ($mail_bcc) $reply_header .= "Bcc: {$mail_bcc}\n";
		$reply_header .= "X-Mailer: {$x_mailer}";
		include ('reply.php');
		$reply_message = html_entity_decode($reply_message, ENT_QUOTES, $internal_enc);
		$reply_message = str_replace("<br />", "", $reply_message);
		$reply_message = str_replace("\t", "\n", $reply_message);
		$reply_message = mb_convert_encoding($reply_message, $internal_enc, 'AUTO');
		mb_send_mail($mail_from, $reply_subject, $reply_message, $reply_header);
	}
	$_SESSION = array(); 
	session_unset();
	session_destroy();
	header('Location: completion.html');
	break;

default: // 入力データ処理
	session_unset();
	foreach ($_POST as $key => $value) {
		list($name, $option) = explode(";", $key);
		if ($option == 's' && !$value) {
			$_SESSION[$name] = '<span class="ERR">必須項目です</span>';
			$error = 1;
		} elseif ($name == 'email' && $value) {
			if (!preg_match("/^[\w\-\.]+\@[\w\-\.]+\.([a-z]+)$/", $value)) {
				$_SESSION['email'] = '<span class="ERR">メールアドレスが正しく入力されてません</span>';
				$error = $email = 1;
			} else {
				$_SESSION['email'] = $email = $value;
			}
		} elseif ($name == 'emailcheck') {
			if ($email != 1 && $email != $value) {
				$_SESSION['email'] = "メールアドレスが一致しません";
				$error = 1;
			}
		} else {
			if (is_array($value)) {
				$value = implode("\t", $value);
			}
		if (get_magic_quotes_gpc()) $value = stripslashes($value);
		$value = mb_convert_encoding($value, $internal_enc, 'AUTO');
		$value = mb_convert_kana($value, 'KV');
		$value = htmlspecialchars($value, ENT_QUOTES);
		$_SESSION[$name] = nl2br($value);
		}
	}
	$_SESSION['inputErr'] = $error;
	header('Location: confirm.php');
}
exit;


function Err($err) { // エラー表示用
	$internal_enc = $GLOBALS['internal_enc'];
	echo <<<EOM
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset={$internal_enc}" />
<title>エラー：$err</title></head>
<body style="font-size: 12px; line-height: 1.8em;">
<strong>エラー : </strong>$err<br>
<input type="button" value="戻る" onclick="history.back();">
  <script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
  
    ga('create', 'UA-51728793-1', 'wmegane.com');
    ga('send', 'pageview');
    ga(‘set’, ‘&uid’, {{USER_ID}}); // ログインしている user_id を使用してUser-ID を設定します。  
  </script>

</body></html>
EOM;
	exit;
}

?>
