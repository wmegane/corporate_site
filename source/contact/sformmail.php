<?php

/*--------------------------------------------------------------------------
	フォームメール - sformmmail2
	(c)Sapphirus.Biz

	※このスクリプトの文字エンコードは euc-jp から変更しないで下さい。
--------------------------------------------------------------------------*/
$script_version = '2.71'; // プログラムバージョン


/*
	追加オプション設定
--------------------------------------------------------------------------*/
// リファラによる外部ドメインからの使用の制限（する[1]/しない[0]）
$ref_check = 1;

// 文字化けする場合は[1]にしてみて下さい
$ill_char = 0;

// 特定の入力文字に「\」が付いてしまう場合は[1]にしてみて下さい
$ill_slash = 0;

// httpsでの利用の場合1にして下さい
// ※ドメインにsecureもしくはsslが含まれる場合は常に設定されます
$use_ssl = 0;

// ブラウザを閉じる（セッション破棄）まで再送防止（する[1]/しない[0]）
$resendCheck = 1;

// フォームHTMLの文字エンコードを設定する場合（sjis／euc-jp／utf-8）
// ※設定しない場合 sfm_form.html の文字エンコードに合わせます
$baseEnc = '';

// 基本設定＆HTML＆メールテンプレート設定ファイル
$temp_html = array(
	'config'		=> 'sfm_config.php' // 基本設定
,	'form'			=> 'sfm_form.html' // 入力フォーム用
,	'confirm'		=> 'sfm_confirm.html' // 確認画面用
,	'completion'	=> 'sfm_completion.html' // 送信完了画面
,	'mail'			=> 'sfm_mail_tmpl.php' // メール送信用
,	'reply'			=> 'sfm_reply_tmpl.php' // 自動返信メール用
);

// エラー表示設定
$temp_err = array(
	'__Error_Input_Data__'		=> '必須項目です'
,	'__Error_Marge_Data__'		=> '入力された内容に不足があります'
,	'__Error_Mail_Address__'	=> 'メールアドレスが正しくありません'
,	'__Error_Mail_Check__'		=> 'メールアドレスが一致しません'
,	'__Error_Text_Max__'		=> '文字数が多すぎます（__Text_Max__字まで）'
);

// エラー表示用HTML
$temp_err_result = array(
	'form'		=> '<div><span class="ERR">__Result__</span></div>' // フォーム画面用
,	'confirm'	=> '<span class="ERR">__Result__</span>' // 確認画面用
);

// 同NAMEの複数項目の結合設定
$name_marge = array(
	'tel'		=> '-'
,	'fax'		=> '-'
,	'zip'		=> '-'
,	'address'	=> "\n"
);

// submit表示項目
function printSubmit($error)
{
	// 送信／戻るボタン表示設定
	$button_text = array(
		'send'	=> '送 信'
	,	'back'	=> '戻 る'
	);
	if ($error == 1) {
		// エラーがある場合のHTML出力
		$submit = <<< EOD
<p class="ERR">必須項目の入力に不足があります。</p>
<input type="button" name="back" id="back" value="{$button_text['back']}" onclick="history.back()" />
EOD;
	} else {
		// 項目が正しい場合のHTML出力
		$submit = <<< EOD
<p>入力が正しければ、送信ボタンを押して下さい。</p>
<input type="hidden" name="mode" id="mode" value="SEND" />
<input type="submit" name="submit" id="submit" value="{$button_text['send']}" />
<input type="button" name="back" id="back" value="{$button_text['back']}" onclick="history.back()" />
EOD;
	}
	return $submit;
}


/*
	メインプログラム
--------------------------------------------------------------------------*/
// 設定ファイル読み込み
require_once($temp_html['config']);

// 設定を配列に格納
$sfm_cfg = array(
	'ref_check'			=> $ref_check
,	'ill_char'			=> $ill_char
,	'ill_slash'			=> $ill_slash
,	'resendCheck'		=> $resendCheck
,	'use_ssl'			=> $use_ssl
,	'mailTo'			=> $mailTo
,	'baseEnc'			=> $baseEnc
,	'maxText'			=> $maxText
,	'mailBcc'			=> $mailBcc
,	'replyBcc'			=> $replyBcc
,	'replyAddress'		=> $replyAddress
,	'replyName'			=> $replyName
,	'returnPath'		=> $returnPath
,	'temp_html'			=> $temp_html
,	'temp_err'			=> $temp_err
,	'temp_err_result'	=> $temp_err_result
,	'name_marge'		=> $name_marge
,	'script_version'	=> $script_version
);
$sfm_cfg['temp_err']['__Error_Text_Max__'] = preg_replace('/__Text_Max__/', $sfm_cfg['maxText'], $sfm_cfg['temp_err']['__Error_Text_Max__']);
$sfm_cfg['creator'] = 'Sapphirus.Biz';
if (!$baseEnc) {
	$baseEnc = file_get_contents($temp_html['form']);
	$sfm_cfg['baseEnc'] = mb_detect_encoding($baseEnc, 'euc-jp, sjis, utf-8');
}

// 内部エンコードを設定（euc-jp 固定）
$sfm_cfg['internal_enc'] = 'euc-jp';
new SbFormMailClass($sfm_cfg);
exit;


/* クラス定義 */
class SbFormMailClass
{
	var $cfg;
	function SbFormMailClass($cfg)
	{
		$this->cfg = $cfg;
		if (!extension_loaded('mbstring')) {
			$this->exitErr('マルチバイト関数が利用できません。');
		}
		mb_language('ja');
		mb_internal_encoding($cfg['internal_enc']);

		// リバースプロクシに対応
		$_SERVER['HTTP_HOST'] = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ?
		$_SERVER['HTTP_X_FORWARDED_HOST'] : $_SERVER['HTTP_HOST'];
		$_SERVER['REMOTE_ADDR'] = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ?
		$_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
		$_SERVER['SERVER_NAME'] = isset($_SERVER['HTTP_X_FORWARDED_SERVER']) ?
		$_SERVER['HTTP_X_FORWARDED_SERVER'] : $_SERVER['SERVER_NAME'];

		// スクリプト名を取得
		$cfg['script_name'] = preg_replace('/.+\/(.*)/', "$1", $_SERVER['REQUEST_URI']);
		// 受け取りアドレスの設定をチェック
		if (!isset($cfg['mailTo'][0])) {
			$this->exitErr('受取先メールアドレスが設定されてません。');
		}

		// モードによる条件分岐
		$mode = '';
		if (isset($_POST['mode'])) {
			$mode = $_POST['mode'];
		}
		if (isset($_POST['back'])) {
			$mode = 'BACK';
		}
		switch ($mode) {
			// メール送信
			case 'SEND':
				session_cache_limiter('nocache');
				session_start();
				if (!isset($_SESSION['SFM'])) {
					$this->exitErr('セッション情報が失われました。一度ブラウザを閉じて下さい。', 1);
				}
				$sfm_mail = $this->formDataMail();
				$sfm_userinfo = $this->userInfo();
				$mailToNum = $_SESSION['SFM']['mailToNum'];
				$cfg['mailTo'] = (isset($cfg['mailTo'][$mailToNum])) ? $cfg['mailTo'][$mailToNum] : $cfg['mailTo'][0];
				// 指定先にメール送信
				$mailFrom = (!isset($_SESSION['SFM']['email'])) ? 'S.B.Formmail' : $_SESSION['SFM']['email'];
				require_once($cfg['temp_html']['mail']);
				$this->sendMail($cfg['mailTo'], $mailSubject, $mailMessage, $mailFrom, $cfg['mailBcc']);
				// メール自動返信
				if ((isset($_POST['autoReply']) || isset($_SESSION['SFM']['autoReply'])) &&
				isset($_SESSION['SFM']['email']) && is_file($cfg['temp_html']['reply'])) {
					require_once($cfg['temp_html']['reply']);
					$replyAddress = ($cfg['replyAddress']) ? $cfg['replyAddress'] : $cfg['mailTo'];
					if ($cfg['replyName']) {
						$replyAddress = "{$cfg['replyName']} <{$replyAddress}>";
					}
					$this->sendMail($_SESSION['SFM']['email'], $replySubject, $replyMessage, $replyAddress, $cfg['replyBcc']);
				}
				unset($_SESSION['SFM']);
				$_SESSION['SFM_TransCheck'] = 1;
				require_once($cfg['temp_html']['completion']);
				break;
			// データ処理と確認
			case 'CONFIRM':
				session_cache_limiter('private_no_expire');
				session_start();
				if ($cfg['resendCheck'] == 1 && $_SESSION['SFM_TransCheck'] == 1) {
					$this->exitErr('送信が完了していますので、一度ブラウザを閉じて下さい。', 1);
				//} elseif ($_SESSION['SFM_TransCheck'] < 0) {
				//	$this->exitErr('著作権表記を確認して下さい。', 1);
				}
				if ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != '') ||
				(preg_match('/secure|ssl/i', $_SERVER['HTTP_HOST'])) ||
				($cfg['use_ssl'] == 1)) {
					$protcol = 'https://';
				} else {
					$protcol = 'http://';
				}
				if ((!preg_match("/{$_SERVER['HTTP_HOST']}/", $_SERVER['HTTP_REFERER'])) && $cfg['ref_check']) {
					$this->exitErr('外部ドメインからの利用はできません');
				}
				unset($_SESSION['SFM']);
				$error = $email = '';
				foreach ($_POST as $key => $value) {
					$name = preg_replace('/(.+)_s$/', "$1", $key);
					if ($value == 'none') {
						$value = '';
					}
					if (is_array($value)) {
						$value = $this->valueMarge($key, $value, $cfg['name_marge']);
						if ($value == '__Error_Marge_Data__') {
							$error = 1;
						}
					}
					if (!$cfg['ill_slash']) {
						$value = (!get_magic_quotes_gpc()) ? addslashes($value) : $value;
					}
					if (!$cfg['ill_char']) {
						$value = mb_convert_encoding($value, $cfg['internal_enc'], $cfg['baseEnc']);
					}
					$value = mb_convert_kana($value, 'KV', $cfg['internal_enc']);
					if (preg_match('/_s$/', $key) && $value == '') {
						$_SESSION['SFM'][$name] = '__Error_Input_Data__';
						$error = 1;
					} elseif ($name == 'email' && $value) {
						if (!preg_match("/^[\w\-\.]+\@[\w\-\.]+\.([a-z]+)$/", $value)) {
							$_SESSION['SFM']['email'] = '__Error_Mail_Address__';
							$error = $email = 1;
						} else {
							$_SESSION['SFM']['email'] = $email = $value;
						}
					} elseif ($name == 'emailcheck') {
						if ($email != 1 && $email != $value) {
							$_SESSION['SFM']['email'] = '__Error_Mail_Check__';
							$error = 1;
						}
					} elseif ($cfg['maxText'] && strlen($value) > $cfg['maxText']) {
						$_SESSION['SFM'][$name] = '__Error_Text_Max__';
						$error = 1;
					} else {
						$_SESSION['SFM'][$name] = $value;
					}
				}
				$_SESSION['SFM']['InputErr'] = $error;
				$sfm_script = $cfg['script_name'];
				$sfm_html = $this->formDataHtml();
				$sfm_submit = mb_convert_encoding(printSubmit($error), $cfg['baseEnc'], $cfg['internal_enc']);
				session_write_close();
				session_cache_limiter('nocache');
				session_start();
				require_once($cfg['temp_html']['confirm']);
				break;
			// 入力フォーム表示
			default:
				session_cache_limiter('private_no_expire');
				session_start();
				if ($mode != 'BACK') {
					unset($_SESSION['SFM']);
				}
				$sfm_script = $cfg['script_name'];
				$_SESSION['SFM_TransCheck'] = 0;
				require_once($cfg['temp_html']['form']);
				break;
		}
	}

	// 同NAMEの複数項目の結合処理
	function valueMarge($key, $val, $name_marge)
	{
		$name = preg_replace('/(.+)_s$/', "$1", $key);
		$rep = (array_key_exists($name, $name_marge)) ? $name_marge[$name] : "\t";
		$set_err = 0;
		foreach ($val as $tmp_key => $tmp_val) {
			if ($tmp_val == 'none') $tmp_val = '';
			if (preg_match('/_s$/', $key) && ($tmp_val == '')) $set_err = 1;
			if ($tmp_val == '') unset($val[$tmp_key]);
		}
		if ($set_err == 1 && array_values($val)) return '__Error_Marge_Data__';
		$val = implode($rep, $val);
		return $val;
	}

	// HTMLデータ格納
	function formDataHtml()
	{
		if (!isset($_SESSION['SFM'])) return false;
		$arr = $_SESSION['SFM'];
		$array_data = array();
		foreach ($arr as $key => $val) {
			$array_data[$key] = $this->valDataHtml($val);
		}
		if (!isset($array_data['autoReply'])) $array_data['autoReply'] = '&nbsp;';
		return (object) $array_data;
	}

	// HTMLデータ整形
	function valDataHtml($val)
	{
		$val = (get_magic_quotes_gpc()) ? stripslashes($val) : $val;
		// 表示用に複数項目を改行
		$val = preg_replace('/\t/', "\n", $val);
		$val = htmlspecialchars($val, ENT_QUOTES, $this->cfg['internal_enc']);
		$val = nl2br($val);
		if (preg_match('/__Error_.+__/', $val)) {
			$val = preg_replace('/__Result__/', $this->cfg['temp_err'][$val], $this->cfg['temp_err_result']['confirm']);
		}
		$val = ($val != '') ? $val : '&nbsp;';
		$val = mb_convert_encoding($val, $this->cfg['baseEnc'], $this->cfg['internal_enc']);
		return $val;
	}

	// MAILデータ格納
	function formDataMail()
	{
		if (!isset($_SESSION['SFM'])) return false;
		$arr = $_SESSION['SFM'];
		$array_data = array();
		foreach ($arr as $key => $val) {
			$array_data[$key] = $this->valDataMail($val);
		}
		return (object) $array_data;
	}

	// MAILデータ整形
	function valDataMail($val)
	{
		$val = (get_magic_quotes_gpc()) ? stripslashes($val) : $val;
		// メール用に複数項目をカンマ区切り
		$val = preg_replace('/\t/', ',', $val);
		return $val;
	}

	// メール送信処理
	function sendMail($mailTo, $mailSubject, $mailMessage, $mailFrom, $mailBcc)
	{
		//return; // [debug]
		if (preg_match('/(.+)(\s<.+\@.+>)$/', $mailFrom, $tmp)) {
			$tmp[1] = mb_encode_mimeheader($tmp[1]);
			$mailFrom = $tmp[1].$tmp[2];
		}
		$mailHeader  = "From: {$mailFrom}\n";
		if ($mailBcc) {
			$mailHeader .= "Bcc: {$mailBcc}\n";
		}
		$php_ver = phpversion();
		$mailHeader .= "X-Mailer: {$this->cfg['creator']} Formmail/{$this->cfg['script_version']}(PHP/{$php_ver})";
		$mailMessage = preg_replace('/\r\n|\r/', "\n", $mailMessage);
		if (isset($this->cfg['returnPath']) && $this->cfg['returnPath']) {
			$result = mb_send_mail($mailTo, $mailSubject, $mailMessage, $mailHeader, "-f{$this->cfg['returnPath']}");
		} else {
			$result = mb_send_mail($mailTo, $mailSubject, $mailMessage, $mailHeader);
		}
		return $result;
	}

	// ユーザー情報取得
	function userInfo()
	{
		$remote_addr = @gethostbyaddr($_SERVER['REMOTE_ADDR']);
		$info  = "{$remote_addr}\n";
		$info .= "{$_SERVER['HTTP_USER_AGENT']}\n";
		$info .= date("Y/m/d - H:i:s");
		return $info;
	}

	// CR チェック
	function crCheck()
	{
		if ($_SESSION['SFM_TransCheck'] != 1) {
			$_SESSION['SFM_TransCheck'] = 0;
		}
		$uri = strtolower($this->cfg['creator']);
		$uri = "http://www.{$uri}/";
		$html = <<< EOD
<div id="copyright"><a href="{$uri}">(c) {$this->cfg['creator']}</a></div>

EOD;
	echo mb_convert_encoding($html,  $this->cfg['baseEnc'], $this->cfg['internal_enc']);
		return;
	}

	// エラー表示用HTML
	function exitErr($err, $btn = 0)
	{
		$btn = (!$btn) ? "<input type=\"button\" value=\"戻 る\" onclick=\"history.back()\">" : '';
		echo <<< EOM
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset={$this->cfg['internal_enc']}">
<title>Error: {$err}</title>
</head>
<body style="font-size: 12px; line-height: 1.8em">
<strong>Error: </strong>{$err}<br>
{$btn}
</body>
</html>

EOM;
	exit;
	}
}

/* フォーム内の入力表示 */
function value($name)
{
	global $sfm_cfg;
	$val = '';
	if (isset($_SESSION['SFM'][$name])) {
		$val = $_SESSION['SFM'][$name];
		if (preg_match('/__Error_.+__/', $val)) {
			$val = '';
		} else {
			$val = mb_convert_encoding($val, $sfm_cfg['baseEnc'], $sfm_cfg['internal_enc']);
		}
	}
	echo $val;
}

/* フォーム内のエラー表示 */
function err($name)
{
	global $sfm_cfg;
	$val = '';
	if (isset($_SESSION['SFM'][$name])) {
		$val = $_SESSION['SFM'][$name];
		if (preg_match('/__Error_.+__/', $val)) {
			$val = preg_replace('/__Result__/', $sfm_cfg['temp_err'][$val], $sfm_cfg['temp_err_result']['form']);
			$val = mb_convert_encoding($val, $sfm_cfg['baseEnc'], $sfm_cfg['internal_enc']);
		} else {
			$val = '';
		}
	}
	echo $val;
}

?>
