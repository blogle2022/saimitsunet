<?php
if ($user->usertype != "1") {
	##
	## userDB.premiumStatus�Υ����usertype��1�ξ�硤ͭ������Ǥ��롥
	## ����ʳ��ξ�硤̵������Ǥ��롥
	##
?>
	<td align="center" valign="top" bgcolor="#ffffcc">
		<table width="500" height="500" border="0" cellspacing="3" cellpadding="8">
			<tr>
				<td align="center">
					<?php
					$str = "<br><br>���Υ���ƥ�Ĥ����Ѥ��������ˤϡ�ͭ���ݥ���Ȥ�ɬ�פǤ���<br><br>";
					redRectText($str);
					?>
					<br>
					<br>
					<a href="/stella/info_charge.php">�����Ѱ����</a>
				</td>
			</tr>
			<tr>
		</table>
		</font>
		<font size="+3" color="gray">

		</font>
	</td>
	</tr>
	</table>
	</font>
	<font size="+3" color="gray">
		<hr size="3" width="92%">
	</font>

	</td>
	</tr>
	</table>
	</td>
	</tr>
	<tr>
		<td bgcolor="#cccccc">
			<csobj csref="../footer.html" h="60" occur="12" t="Component" w="800">
				<?
				include("footer_subdir.php");
				?>
			</csobj>
		</td>
	</tr>
	</table>
	</BODY>

	</HTML>
<?php
	exit();
}
?>