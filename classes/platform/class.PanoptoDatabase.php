<?php
declare(strict_types=1);

/**
 * This file is part of the Panopto Repository Object plugin for ILIAS.
 * This plugin allows users to embed Panopto videos in ILIAS as repository objects.
 *
 * The Panopto Repository Object plugin for ILIAS is open-source and licensed under GPL-3.0.
 * For license details, visit https://www.gnu.org/licenses/gpl-3.0.en.html.
 *
 * To report bugs or participate in discussions, visit the Mantis system and filter by
 * the category "Panopto" at https://mantis.ilias.de.
 *
 * More information and source code are available at:
 * https://github.com/surlabs/Panopto
 *
 * If you need support, please contact the maintainer of this software at:
 * info@surlabs.es
 *
 */

namespace platform;

use Exception;
use ilDBInterface;

/**
 * Class PanoptoDatabase
 * @authors Jesús Copado, Daniel Cazalla, Saúl Díaz, Juan Aguilar <info@surlabs.es>
 */
class PanoptoDatabase
{
    private ilDBInterface $db;

    public function __construct()
    {
        global $DIC;

        $this->db = $DIC->database();
    }

    /**
     * Inserts a new row in the database
     *
     * Usage: PanoptoDatabase::insert('table_name', ['column1' => 'value1', 'column2' => 'value2']);
     *
     * @param string $table
     * @param array $data
     * @return void
     * @throws PanoptoException
     */
    public function insert(string $table, array $data): void
    {
        try {
            $this->db->query("INSERT INTO " . $table . " (" . implode(", ", array_keys($data)) . ") VALUES (" . implode(", ", array_map(function ($value) {
                    return $this->db->quote($value);
                }, array_values($data))) . ")");
        } catch (Exception $e) {
            throw new PanoptoException($e->getMessage());
        }
    }

    /**
     * Inserts a new row in the database, if the row already exists, updates it
     *
     * Usage: PanoptoDatabase::insertOnDuplicatedKey('table_name', ['column1' => 'value1', 'column2' => 'value2']);
     *
     * @param string $table
     * @param array $data
     * @return void
     * @throws PanoptoException
     */
    public function insertOnDuplicatedKey(string $table, array $data): void
    {
        try {
            $this->db->query("INSERT INTO " . $table . " (" . implode(", ", array_keys($data)) . ") VALUES (" . implode(", ", array_map(function ($value) {
                    return $this->db->quote($value);
                }, array_values($data))) . ") ON DUPLICATE KEY UPDATE " . implode(", ", array_map(function ($key, $value) {
                    return $key . " = " . $value;
                }, array_keys($data), array_map(function ($value) {
                    return $this->db->quote($value);
                }, array_values($data)))));
        } catch (Exception $e) {
            throw new PanoptoException($e->getMessage());
        }
    }

    /**
     * Updates a row/s in the database
     *
     * Usage: PanoptoDatabase::update('table_name', ['column1' => 'value1', 'column2' => 'value2'], ['id' => 1]);
     *
     * @param string $table
     * @param array $data
     * @param array $where
     * @return void
     * @throws PanoptoException
     */
    public function update(string $table, array $data, array $where): void
    {
        try {
            $this->db->query("UPDATE " . $table . " SET " . implode(", ", array_map(function ($key, $value) {
                    return $key . " = " . $value;
                }, array_keys($data), array_map(function ($value) {
                    return $this->db->quote($value);
                }, array_values($data)))) . " WHERE " . implode(" AND ", array_map(function ($key, $value) {
                    return $key . " = " . $value;
                }, array_keys($where), array_map(function ($value) {
                    return $this->db->quote($value);
                }, array_values($where)))));
        } catch (Exception $e) {
            throw new PanoptoException($e->getMessage());
        }
    }

    /**
     * Deletes a row/s in the database
     *
     * Usage: PanoptoDatabase::delete('table_name', ['id' => 1]);
     *
     * @param string $table
     * @param array $where
     * @return void
     * @throws PanoptoException
     */
    public function delete(string $table, array $where): void
    {
        try {
            $this->db->query("DELETE FROM " . $table . " WHERE " . implode(" AND ", array_map(function ($key, $value) {
                    return $key . " = " . $value;
                }, array_keys($where), array_map(function ($value) {
                    return $this->db->quote($value);
                }, array_values($where)))));
        } catch (Exception $e) {
            throw new PanoptoException($e->getMessage());
        }
    }

    /**
     * Selects a row/s in the database
     *
     * Usage: PanoptoDatabase::select('table_name', ['id' => 1]);
     *
     * @param string $table
     * @param array|null $where
     * @param array|null $columns
     * @param string|null $extra
     * @return array
     * @throws PanoptoException
     */
    public function select(string $table, ?array $where = null, ?array $columns = null, ?string $extra = ""): array
    {
        try {
            $query = "SELECT " . (isset($columns) ? implode(", ", $columns) : "*") . " FROM " . $table;

            if (isset($where)) {
                $query .= " WHERE " . implode(" AND ", array_map(function ($key, $value) {
                        return $key . " = " . $value;
                    }, array_keys($where), array_map(function ($value) {
                        return $this->db->quote($value);
                    }, array_values($where))));
            }

            $query .= " " . $extra;

            $result = $this->db->query($query);

            $rows = [];

            while ($row = $this->db->fetchAssoc($result)) {
                $rows[] = $row;
            }

            return $rows;
        } catch (Exception $e) {
            throw new PanoptoException($e->getMessage());
        }
    }

    /**
     * Returns the next id for a table
     *
     * Usage: PanoptoDatabase::nextId('table_name');
     *
     * @param string $table
     * @return int
     * @throws PanoptoException
     */
    public function nextId(string $table): int
    {
        try {
            return $this->db->nextId($table);
        } catch (Exception $e) {
            throw new PanoptoException($e->getMessage());
        }
    }
}