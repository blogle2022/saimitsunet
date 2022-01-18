            <form name="form" method="post" action="/servlet/daily">
              <table width="460" border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td align="center"><table width="100%" border="0" cellspacing="5" cellpadding="3">
                      <tr>
                        <td align="center" class="txt16b2">今日のあなた</td>
                      </tr>
                      <tr>
                        <td align="center" class="txt14"><font color="#9933CC"><b>〜今日の運勢の6項目<br>
                          （ラブ運・マネー運・健康運・ビジネス運・対人運・セックス運）<br>
                          について占います〜</b></font></td>
                      </tr>
                      <tr>
                        <td align="center" class="txt12">&nbsp;</td>
                      </tr>
                      <tr>
                        <td align="center" class="txt12"><b>以下の項目を入力してください</b><br>
                          (「生年月日(西暦)」は必ず「半角数字」で入力してください)<br>
                        </td>
                      </tr>
                    </table></td>
                </tr>
                <tr bordercolor="#66CC00" bordercolorlight="#FFFF99" bordercolordark="#DD9804" bgcolor="#FDFDE5">
                  <td><table width="100%"  bgcolor="#FDFDE5" bordercolorlight="#FFFF99" bordercolordark="#DD9804" bordercolor="#66CC00"  cellspacing="0" cellpadding="15" border="2">
                      <tr>
                        <td><table width="100%"  cellspacing="2" cellpadding="0">
                            <tr>
                              <td width="25%"><div align="right">氏名　：</div></td>
                              <td width="75%"><INPUT NAME="name" value="" SIZE="38">
                              　</td>
                            </tr>
                            <tr bgcolor="#FDFDE5">
                              <td width="25%"><div align="right">性別　：</div></td>
                              <td width="75%">　
                                <input type="radio" name="sex" value="male" >
                                男
                                <input type="radio" name="sex" value="female" >
                                女</td>
                            </tr>
                            <tr bgcolor="#FDFDE5">
                              <td width="25%"><div align="right">結婚　：</div></td>
                              <td width="75%"><input type="radio" name="marriage" value="marriaged" >
                                既婚
                                <input type="radio" name="marriage" value="single" >
                                独身 　</td>
                            </tr>
                            <tr bgcolor="#FDFDE5">
                              <td width="25%"><div align="right">生年月日　：</div></td>
                              <td width="75%">西暦
                                <INPUT NAME="year"  value="" SIZE="5">
                                年
                                <select name="mon" size="1">
                                  <option value="1">1</option>
                                  <option value="2">2</option>
                                  <option value="3">3</option>
                                  <option value="4">4</option>
                                  <option value="5">5</option>
                                  <option value="6">6</option>
                                  <option value="7">7</option>
                                  <option value="8">8</option>
                                  <option value="9">9</option>
                                  <option value="10">10</option>
                                  <option value="11">11</option>
                                  <option value="12">12</option>
                                </select>
                                月
                                <select name="day" size="1">
                                  <option value="1">1</option>
                                  <option value="2">2</option>
                                  <option value="3">3</option>
                                  <option value="4">4</option>
                                  <option value="5">5</option>
                                  <option value="6">6</option>
                                  <option value="7">7</option>
                                  <option value="8">8</option>
                                  <option value="9">9</option>
                                  <option value="10">10</option>
                                  <option value="11">11</option>
                                  <option value="12">12</option>
                                  <option value="13">13</option>
                                  <option value="14">14</option>
                                  <option value="15">15</option>
                                  <option value="16">16</option>
                                  <option value="17">17</option>
                                  <option value="18">18</option>
                                  <option value="19">19</option>
                                  <option value="20">20</option>
                                  <option value="21">21</option>
                                  <option value="22">22</option>
                                  <option value="23">23</option>
                                  <option value="24">24</option>
                                  <option value="25">25</option>
                                  <option value="26">26</option>
                                  <option value="27">27</option>
                                  <option value="28">28</option>
                                  <option value="29">29</option>
                                  <option value="30">30</option>
                                  <option value="31">31</option>
                                </select>
                                日</td>
                            </tr>
                            <tr bgcolor="#FDFDE5">
                              <td width="25%"><div align="right">生まれた時刻　：</div></td>
                              <td width="75%"><select name="am_pm" onChange="ampmChk(this)">
                                  <option value="0" >不明</option>
                                  <option value="1" >午前</option>
                                  <option value="2" >午後</option>
                                </select>
                                　
                                <select name="hour" size="1" disabled>
                                  <option value=""></option>
                                  <option value="0">0</option>
                                  <option value="1">1</option>
                                  <option value="2">2</option>
                                  <option value="3">3</option>
                                  <option value="4">4</option>
                                  <option value="5">5</option>
                                  <option value="6">6</option>
                                  <option value="7">7</option>
                                  <option value="8">8</option>
                                  <option value="9">9</option>
                                  <option value="10">10</option>
                                  <option value="11">11</option>
                                </select>
                                時
                                <select name="min" size="1" disabled>
                                  <option value=""></option>
                                  <option value="0">0</option>
                                  <option value="1">1</option>
                                  <option value="2">2</option>
                                  <option value="3">3</option>
                                  <option value="4">4</option>
                                  <option value="5">5</option>
                                  <option value="6">6</option>
                                  <option value="7">7</option>
                                  <option value="8">8</option>
                                  <option value="9">9</option>
                                  <option value="10">10</option>
                                  <option value="11">11</option>
                                  <option value="12">12</option>
                                  <option value="13">13</option>
                                  <option value="14">14</option>
                                  <option value="15">15</option>
                                  <option value="16">16</option>
                                  <option value="17">17</option>
                                  <option value="18">18</option>
                                  <option value="19">19</option>
                                  <option value="20">20</option>
                                  <option value="21">21</option>
                                  <option value="22">22</option>
                                  <option value="23">23</option>
                                  <option value="24">24</option>
                                  <option value="25">25</option>
                                  <option value="26">26</option>
                                  <option value="27">27</option>
                                  <option value="28">28</option>
                                  <option value="29">29</option>
                                  <option value="30">30</option>
                                  <option value="31">31</option>
                                  <option value="32">32</option>
                                  <option value="33">33</option>
                                  <option value="34">34</option>
                                  <option value="35">35</option>
                                  <option value="36">36</option>
                                  <option value="37">37</option>
                                  <option value="38">38</option>
                                  <option value="39">39</option>
                                  <option value="40">40</option>
                                  <option value="41">41</option>
                                  <option value="42">42</option>
                                  <option value="43">43</option>
                                  <option value="44">44</option>
                                  <option value="45">45</option>
                                  <option value="46">46</option>
                                  <option value="47">47</option>
                                  <option value="48">48</option>
                                  <option value="49">49</option>
                                  <option value="50">50</option>
                                  <option value="51">51</option>
                                  <option value="52">52</option>
                                  <option value="53">53</option>
                                  <option value="54">54</option>
                                  <option value="55">55</option>
                                  <option value="56">56</option>
                                  <option value="57">57</option>
                                  <option value="58">58</option>
                                  <option value="59">59</option>
                                </select>
                                分 </td>
                            </tr>
                            <tr bgcolor="#FDFDE5">
                              <td width="25%">　 </td>
                              <td width="75%">　 </td>
                            </tr>
                            <tr bgcolor="#FDFDE5">
                              <td width="25%"><div align="right">出生地　：</div></td>
                              <td width="75%"><select name="submit1" onChange="setW1(this.form.submit1,this.form.w1,this.form.w2,this.form.where)">
                                  <option value="0"  selected>選択</option>
                                  <option value="1" >国内</option>
                                  <option value="2" >海外</option>
                                </select>
                                <input type="text" name="where" value="" disabled>
                                <br>
                                <select name="w1" onChange="setW2(this.form.submit1,this.form.w1,this.form.w2,this.form.where)" STYLE="width:22ex">
                                  <option value="0" >↑選んでください</option>
                                </select>
                                <select name="w2" onChange="setWhere(this.form.submit1,this.form.w1,this.form.w2,this.form.where)" STYLE="width:20ex">
                                  <option value="0" >←選んでください</option>
                                </select>
                              </td>
                            </tr>
                          </table></td>
                      </tr>
                    </table></td>
                </tr>
                <tr>
                  <td align="center"><table width="10" border="0" cellspacing="1" cellpadding="0">
                      <tr>
                        <td>　</td>
                      </tr>
                    </table>
                    <input name="submit" type="submit" class="txt12"  value="占い開始" onClick="enable4(this)">
                    <input type="button" value="クリアー" name="reset" onClick="setData()">
                    <table width="10" border="0" cellspacing="1" cellpadding="0">
                      <tr>
                        <td>　</td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
            </form>
