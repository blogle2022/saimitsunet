<?
$subject["newreg"] = 
	"=?ISO-2022-JP?B?GyRCRVBPPyQiJGokLCRIJCYbKEI=?=" . "\n" .
 	" =?ISO-2022-JP?B?GyRCJDQkNiQkJF4kORsoQiAtIBskQjpZTClAakAxPVEbKEIgLQ==?=";


$mbody["newreg"] = <<<EOF
<% NAME %>さん、細密占星術へ登録いただきまして、まことにありがとうございます。
登録内容は以下の通りです。

ユーザID： <% MAIL %>
パスワード： <% PASSWORD %>

ログインはこちらから
http://janus.saimitsu.jp/stella/

EOF;

$subject["lostpass"] = "=?ISO-2022-JP?B?GyRCOllMKUBqQDE9URsoQg==?="; 

$mbody["lostpass"] = <<<EOF
登録内容は以下の通りです。

ユーザID： <% MAIL %>
パスワード： <% PASSWORD %>

ログインはこちらから
http://janus.saimitsu.jp/stella/

EOF;


?>
