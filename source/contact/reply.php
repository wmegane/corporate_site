<?php
//ユーザー情報
$user  = @gethostbyaddr($_SERVER['REMOTE_ADDR']) . "\n";
$user .= $_SERVER['HTTP_USER_AGENT'] . "\n";
$user .= date("Y/m/d - H:i:s");

//送信メッセージ
$reply_message = <<< EOM
以下の内容で送信を受け付けました。
────────────────────────────────────
■名前
{$_SESSION['name']}

■メールアドレス
{$_SESSION['email']}

■メッセージ
{$_SESSION['message']}
────────────────────────────────────
□ユーザー情報
{$user}
EOM;
?>