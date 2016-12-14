<?php

/*--------------------------------------------------------------------------
	フォームメール - sformmmail2
	(c)Sapphirus.Biz

	※このスクリプトの文字エンコードは euc-jp から変更しないで下さい。
--------------------------------------------------------------------------*/

// 自動返信のSubject（件名）
$replySubject = 'メールを受け付けました';

//送信メッセージ
$replyMessage = <<< EOD
以下の内容でフォームからの送信を受け付けました。
────────────────────────────────────
■氏名
{$sfm_mail->name}

■ふりがな
{$sfm_mail->kana}

■郵便番号
{$sfm_mail->zip}

■都道府県
{$sfm_mail->address1}

■市区町村・番地
{$sfm_mail->address2}

■マンション等
{$sfm_mail->address3}

■電話番号
{$sfm_mail->tel}

■ファックス
{$sfm_mail->fax}

■メールアドレス
{$sfm_mail->email}

■性別
{$sfm_mail->sex}

■好きな食べ物は？
{$sfm_mail->food}

■件名
{$sfm_mail->subject}

■メッセージ
{$sfm_mail->message}
────────────────────────────────────
□ユーザー情報
$sfm_userinfo
EOD;

?>
