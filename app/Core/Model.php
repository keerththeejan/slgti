<?php

declare(strict_types=1);

namespace App\Core;

use PDOStatement;

class Model
{
    public static function query(string $sql, array $params = []): PDOStatement
    {
        $statement = Database::connection()->prepare($sql);
        $statement->execute($params);
        return $statement;
    }

    public static function fetchAll(string $sql, array $params = []): array
    {
        return self::query($sql, $params)->fetchAll();
    }

    public static function fetch(string $sql, array $params = []): ?array
    {
        $row = self::query($sql, $params)->fetch();
        return $row ?: null;
    }

    public static function insert(string $table, array $data): int
    {
        $table = ident($table);
        $columns = array_map('ident', array_keys($data));
        $placeholders = implode(',', array_fill(0, count($columns), '?'));
        $sql = 'INSERT INTO `' . $table . '` (`' . implode('`,`', $columns) . '`) VALUES (' . $placeholders . ')';
        self::query($sql, array_values($data));
        return (int) Database::connection()->lastInsertId();
    }

    public static function updateById(string $table, int $id, array $data): void
    {
        if ($data === []) {
            return;
        }
        $table = ident($table);
        $sets = implode(', ', array_map(static fn (string $column): string => '`' . ident($column) . '` = ?', array_keys($data)));
        self::query('UPDATE `' . $table . '` SET ' . $sets . ' WHERE id = ?', [...array_values($data), $id]);
    }

    public static function deleteById(string $table, int $id): void
    {
        self::query('DELETE FROM `' . ident($table) . '` WHERE id = ?', [$id]);
    }
}
