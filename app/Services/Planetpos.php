<?php

namespace App\Services;

use PDO;
use Exception;

/**
 * 一時的に使用するDB処理クラス
 */
class Planetpos
{
    public function __construct()
    {
        $dbconn = config('database.dbconn');
        $dbhost = config('database.dbhost');
        $dbname = config('database.dbname');
        $dbuser = config('database.dbuser');
        $dbpass = config('database.dbpass');
        $dbport = config('database.dbport');
        $dsn = "$dbconn:dbname=$dbname;host=$dbhost:$dbport";
        $this->dbh = $dbh = new PDO($dsn, $dbuser, $dbpass, [PDO::ATTR_EMULATE_PREPARES => false]);
        $this->dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->table = 'planetpos';
        $sql = "DESCRIBE `planetpos`";
        $sth = $dbh->prepare($sql);
        $result = $sth->execute();
        if (!$result) {
            throw new Exception('テーブルが存在しません。');
        }
    }

    public function find(string $findCol, string $from, string $to)
    {
        $table = $this->table;
        $sql = "SELECT * FROM $table WHERE $findCol BETWEEN :fromVal AND :toVal";
        $sth = $this->dbh->prepare($sql);
        $sth->execute([
            'fromVal' => $from,
            'toVal' => $to,
        ]);

        $records = $sth->fetchAll(PDO::FETCH_ASSOC);

        return $records;
    }

    public function aspect(string $table, array $where)
    {

        $whereConditions = [];

        foreach ($where as $key => $value) {
            $whereConditions[] = "$key='$value'";
        }

        $whereQuery = implode(' AND ', $whereConditions);

        $sql = "SELECT FT_TXT FROM $table WHERE $whereQuery;";
        try {
            $sth = $this->dbh->prepare($sql);
            $sth->execute();

            $records = $sth->fetchAll(PDO::FETCH_ASSOC);
        } catch (\Throwable $th) {
            var_dump($th);
        }


        return $records;
    }
}
