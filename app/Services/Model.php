<?php

namespace App\Services;

use Dotenv\Util\Str;
use Exception;
use PDO;

class Model
{
    private $dbh;
    private $table;

    public function __construct(string $table)
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
        $this->table = $table;
        $sql = "DESCRIBE `$table`";
        $sth = $dbh->prepare($sql);
        $result = $sth->execute();
        if (!$result) {
            throw new Exception('テーブルが存在しません。');
        }
    }

    public function get(string $key = 'id', string $operand = '=', $value = '')
    {
        $table = $this->table;
        $sql = "SELECT * FROM $table WHERE $key$operand:val";
        $sth = $this->dbh->prepare($sql);
        $sth->execute([
            'val' => $value,
        ]);

        $records = $sth->fetchAll(PDO::FETCH_ASSOC);
        $record = $records[0];

        return $record;
    }

    public function find(string $key = 'id', string $operand = '=', $value = '')
    {
        $table = $this->table;
        $sql = "SELECT * FROM $table WHERE $key$operand:val";
        $sth = $this->dbh->prepare($sql);
        $sth->execute([
            'val' => $value,
        ]);

        $records = $sth->fetchAll(PDO::FETCH_ASSOC);
        $record = $records;

        return $record;
    }

    public function addSingle(array $data)
    {
        $table = $this->table;
        $columns = implode(", ", array_keys($data));
        $placeholder = ':' . implode(", :", array_keys($data));
        $sql = "INSERT INTO $table ($columns) VALUES ($placeholder)";
        $sth = $this->dbh->prepare($sql);
        $status = $sth->execute($data);
        return $status;
    }

    public function updateOrInsert(array $data, $dupKey)
    {
        if (!array_key_exists($dupKey, $data)) {
            return false;
        }

        $table = $this->table;
        $dupKeyValue = $data[$dupKey];
        $checkExistsSql = "SELECT COUNT(*) AS count FROM $table WHERE $dupKey='$dupKeyValue'";
        $dataCountSth = $this->dbh->prepare($checkExistsSql);
        $dataCountSth->execute();
        $count = $dataCountSth->fetch();

        $columns = implode(", ", array_keys($data));
        $placeholder = ':' . implode(", :", array_keys($data));
        if ($count['count']) {
            $updateData = [];
            foreach ($data as $key => $value) {
                $updateData[] = "$key = :$key";
            }
            $setQuery = implode(", ", $updateData);
            $sql = "UPDATE $table SET $setQuery WHERE $dupKey='$dupKeyValue'";
            $sth = $this->dbh->prepare($sql);
            $status = $sth->execute($data);
        } else {
            $sql = "INSERT INTO $table ($columns) VALUES ($placeholder)";
            $sth = $this->dbh->prepare($sql);
            $status = $sth->execute($data);
        }

        return $status;
    }

    public function delete(string $key, string $value)
    {
        $table = $this->table;
        $sql = "DELETE FROM $table WHERE $key = '$value'";
        $sth = $this->dbh->prepare($sql);
        $status = $sth->execute();

        return $status;
    }
}
