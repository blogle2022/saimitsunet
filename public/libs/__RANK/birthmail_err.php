<HTML lang="ja">

<HEAD>
	<TITLE>�ꤤɴ��Ź Stella</TITLE>
	<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=EUC-JP">
	<csscriptdict import>
		<script type="text/javascript" src="../GeneratedItems/CSScriptLib.js"></script>
	</csscriptdict>
	<csimport user="bt.html" occur="52">
		<csactiondict>
			<script type="text/javascript">
				<!--
				CSInit[CSInit.length] = new Array(CSILoad, /*CMP*/ 'button2', /*URL*/ 'images/bt_01.gif', /*URL*/ 'images/btr_01.gif', /*URL*/ '', '');
				CSInit[CSInit.length] = new Array(CSILoad, /*CMP*/ 'button3', /*URL*/ 'images/bt_02.gif', /*URL*/ 'images/btr_02.gif', /*URL*/ '', '');
				CSInit[CSInit.length] = new Array(CSILoad, /*CMP*/ 'button4', /*URL*/ 'images/bt_03.gif', /*URL*/ 'images/btr_03.gif', /*URL*/ '', '');
				CSInit[CSInit.length] = new Array(CSILoad, /*CMP*/ 'button5', /*URL*/ 'images/bt_04.gif', /*URL*/ 'images/btr_04.gif', /*URL*/ '', '');
				CSInit[CSInit.length] = new Array(CSILoad, /*CMP*/ 'button6', /*URL*/ 'images/bt_05.gif', /*URL*/ 'images/btr_05.gif', /*URL*/ '', '');
				CSInit[CSInit.length] = new Array(CSILoad, /*CMP*/ 'button7', /*URL*/ 'images/bt_06.gif', /*URL*/ 'images/btr_06.gif', /*URL*/ '', '');
				CSInit[CSInit.length] = new Array(CSILoad, /*CMP*/ 'button8', /*URL*/ 'images/bt_07.gif', /*URL*/ 'images/btr_07.gif', /*URL*/ '', '');
				CSInit[CSInit.length] = new Array(CSILoad, /*CMP*/ 'button9', /*URL*/ 'images/bt_08.gif', /*URL*/ 'images/btr_08.gif', /*URL*/ '', '');
				CSInit[CSInit.length] = new Array(CSILoad, /*CMP*/ 'button10', /*URL*/ 'images/bt_09.gif', /*URL*/ 'images/btr_09.gif', /*URL*/ '', '');
				CSInit[CSInit.length] = new Array(CSILoad, /*CMP*/ 'button11', /*URL*/ 'images/bt_10.gif', /*URL*/ 'images/btr_10.gif', /*URL*/ '', '');

				// 
				-->
			</script>
		</csactiondict>
	</csimport>
	<link href="site.css" rel="stylesheet" type="text/css">
</HEAD>

<BODY onload="CSScriptInit();" BGCOLOR=#cccccc LEFTMARGIN=0 TOPMARGIN=0 MARGINWIDTH=0 MARGINHEIGHT=0>
	<table width="800" border="0" cellspacing="0" cellpadding="0" bgcolor="white">
		<tr height="89">
			<td height="89"><a href="menuindex.php"><img src="header/2header1.gif" alt="" width="799" height="89" border="0"></a></td>
		</tr>
		<tr height="20">
			<td align="center" height="20">
				<csobj csref="bt.html" h="20" occur="52" t="Component" w="800">
					<?
					include("navi_root.php");
					?>
				</csobj>
			</td>
		</tr>
		<tr height="450">
			<td align="center" bgcolor="#ffffcc" height="450">



				<script language="JavaScript" src="./zip.js">
				</script>
				<table width="556" border="0" cellpadding="5" cellspacing="0">
					<tr>
						<td align="center" class="txt12b">
							<table width="550" border="0" cellpadding="0" cellspacing="0" bgcolor="#35A4AF">
								<tr>
									<td>


										<TABLE width="100%" height="100%" border="0" cellpadding="3" cellspacing="1" class="txt14">
											<TBODY>

												<TR>
													<TD align="center" valign="top" bgcolor="#FFFFFF"><b>
															���Ϥ˸��ꡢ�ޤ���̤���Ϲ��ܤ�����ޤ���<br>�֥饦���Ρ����ץܥ���򲡤������β��̤���äƤ���������
														</b>
													</TD>
												</TR>

												<TR>
													<TD valign="top" bgcolor="#FFFFFF">
														<?
														foreach ($errstr as $e) {
														?>
															<li><b>
																	<font color="#FF6666"><? echo $e; ?></font>
																</b>
															<?
														}
															?>
													</TD>
												</TR>


										</TABLE>




									</TD>
								</TR>
								</TBODY>
							</TABLE>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	</FORM>










	</td>
	</tr>
	<tr>
		<td bgcolor="#cccccc">
			<csobj csref="footer.html" h="60" occur="12" t="Component" w="800">
				<?
				include("footer.php");
				?>
			</csobj>
		</td>
	</tr>
	</table>
</BODY>

</HTML>