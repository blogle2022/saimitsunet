<?php

require_once __DIR__ . '/../../bootstrap/page.php';

if ($request->method() === 'post') {
    $tranFilePath = array_to_path([storage_path(), 'system', 'future_total.json']);
    $tranFile = file_get_contents($tranFilePath);
    $tranList = json_decode($tranFile, true);
    $tranKeys = array_keys($tranList);
    $saveData = [];
    foreach ($tranKeys as $key) {
        $saveData[$key] = explode(',', $request->post()[$key] ?? $tranList[$key]);
    }

    $saveDataJson = json_encode($saveData, JSON_UNESCAPED_UNICODE);
    file_put_contents($tranFilePath, $saveDataJson);

    redirect('/admin');
}
