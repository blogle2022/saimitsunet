<?php

require_once __DIR__ . '/../../bootstrap/page.php';

if ('post' === $request->method()) :

    if ($request->post('year')) :
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
    endif;
elseif ('get' === $request->method('get')) : ?>

    <?php if (!$_SESSION['admin_loggedin']) : ?>
        <form action="" method="POST">
            <label>
                PASSWORD: <input type="password" name="password">
            </label>
            <div>
                <button type="submit">ログインする</button>
            </div>
        </form>
    <?php else : ?>
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
    <?php endif; ?>

<?php endif; ?>