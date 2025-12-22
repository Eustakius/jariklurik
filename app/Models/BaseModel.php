<?php

namespace App\Models;

use CodeIgniter\Model;
use CodeIgniter\Database\Exceptions\DataException;
use InvalidArgumentException;

class BaseModel extends Model
{
    /**
     * Custom error container untuk menambahkan error manual
     */
    protected array $customErrors = [];

    /**
     * Tambahkan error custom
     */
    public function setError(string $field, string $message): void
    {
        $this->customErrors[$field] = $message;
    }

    /**
     * Tambahkan banyak error sekaligus
     */
    public function addErrors(array $errors): void
    {
        $this->customErrors = array_merge($this->customErrors, $errors);
    }

    /**
     * Override bawaan CI4 agar gabungkan error bawaan + custom
     */
    public function errors(bool $forceDB = false): array
    {
        $errors = parent::errors($forceDB);
        return array_merge($errors ?? [], $this->customErrors);
    }

    /**
     * Override update() untuk menghentikan proses jika ada custom error
     */
    public function update($id = null, $row = null): bool
    {
        // jalankan beforeUpdate callbacks
        if ($this->tempAllowCallbacks) {
            $eventData = [
                'id' => $id,
                'data' => $row,
            ];
            $eventData = $this->trigger('beforeUpdate', $eventData);
        }
        if ($this->customErrors) {
        // dd('customErrors');
            return false;
        }
     
        // dd($row);
        return parent::update($id, $row);
    }
    public function insert($row = null, bool $returnID = true): bool
    {
        // jalankan beforeUpdate callbacks
        if ($this->tempAllowCallbacks) {
            $eventData = [
                'data' => $row,
            ];
            $eventData = $this->trigger('beforeInsert', $eventData);
        }
        if ($this->customErrors) {
            return false;
        }
     
        return parent::insert($row, $returnID);
    }
}
