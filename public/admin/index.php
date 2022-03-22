<?php

require_once __DIR__ . '/../../bootstrap/page.php';

if ('post' === $request->method()) :
    if ($request->post('year') !== null) :
        $half = [
            'first_half' => ['from' => '0101', 'to' => '0630', 'period' => '上期'],
            'second_half' => ['from' => '0701', 'to' => '1231', 'period' => '下期'],
        ];

        $year = $request->post('year');

        $target = $half[$request->post('timing')];
        $target['year'] = $year;

        $targetJson = json_encode($target, JSON_UNESCAPED_UNICODE);

        file_put_contents(storage_path() . '/system/future_range.json', $targetJson);
        redirect('/admin');
    elseif ($request->post('password')) :
        $hash = file_get_contents(storage_path() . '/system/password.txt');
        $password = $request->post('password');
        $_SESSION['admin_loggedin'] = password_verify($password, $hash);
        redirect('/admin');
    else :
        redirect('/admin');
    endif;
elseif ('get' === $request->method('get')) :
    $tranData = file_get_contents(array_to_path([storage_path(), 'system', 'future_total.json']));
    $tranList = json_decode($tranData, true);
    if (!$_SESSION['admin_loggedin']) : ?>
        <form action="" method="POST">
            <label>
                PASSWORD: <input type="password" name="password">
            </label>
            <div>
                <button type="submit">ログインする</button>
            </div>
        </form>
    <?php else : ?>
        <div style="margin-bottom: 3rem;">
            <h2>未来占いの範囲設定</h2>
            <form action="" method="POST">
                <div>
                    <input type="number" name="year" placeholder="未来占いの結果の年" required>
                </div>

                <div>
                    <select name="timing">
                        <option value="first_half">前半</option>
                        <option value="second_half">後半</option>
                    </select>
                </div>

                <div>
                    <button type="submit">設定する</button>
                </div>
            </form>
        </div>

        <div>
            <h2>未来総合の設定</h2>
            # TODO 送信先とその後の処理を作成
            <form action="/admin/tranlist.php" method="POST">
                <?php foreach ($tranList as $title => $value) : ?>
                    <div style="margin-bottom: 2rem;">
                        <label for="sundays" style="display: block"><?php echo $title; ?></label>
                        <textarea name="<?php echo $title ?>" id="sundays" cols="60" rows="10"><?php echo implode(',', $value); ?></textarea>
                    </div>
                <?php endforeach; ?>

                <div>
                    <button type="submit">送信する</button>
                </div>
            </form>
        </div>
    <?php endif; ?>

<?php endif; ?>