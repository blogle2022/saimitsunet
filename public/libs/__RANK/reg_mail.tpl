<?
$subject["newreg"] = 
	"=?ISO-2022-JP?B?GyRCRVBPPyQiJGokLCRIJCYbKEI=?=" . "\n" .
 	" =?ISO-2022-JP?B?GyRCJDQkNiQkJF4kORsoQiAtIBskQjpZTClAakAxPVEbKEIgLQ==?=";


$mbody["newreg"] = <<<EOF
<% NAME %>���󡢺�̩�����Ѥ���Ͽ���������ޤ��ơ��ޤ��Ȥˤ��꤬�Ȥ��������ޤ���
��Ͽ���Ƥϰʲ����̤�Ǥ���

�桼��ID�� <% MAIL %>
�ѥ���ɡ� <% PASSWORD %>

������Ϥ����餫��
http://janus.saimitsu.jp/stella/

EOF;

$subject["lostpass"] = "=?ISO-2022-JP?B?GyRCOllMKUBqQDE9URsoQg==?="; 

$mbody["lostpass"] = <<<EOF
��Ͽ���Ƥϰʲ����̤�Ǥ���

�桼��ID�� <% MAIL %>
�ѥ���ɡ� <% PASSWORD %>

������Ϥ����餫��
http://janus.saimitsu.jp/stella/

EOF;


?>
