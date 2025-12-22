<?php

namespace App\Entities;

use App\Models\GroupModel;
use CodeIgniter\Entity\Entity;

/**
 * Group Entity
 *
 * As of version 1.2 this class is used by the new GroupModel
 * to allow using a strongly-typed return. Any logic in this
 * class should not be relied on within this library.
 *
 * @since 1.2.0
 */
class Group extends Entity
{
    public function formatDataTableModel()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
        ];
    }
    public function formatDataModel()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
        ];
    }

    public function getPermissionsForGroup(): array
    {
        $groupModel = new GroupModel();

        return $groupModel->getPermissionsForGroup($this->id);
    }
}
