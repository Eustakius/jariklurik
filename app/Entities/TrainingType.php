<?php

namespace App\Entities;

use App\Models\CountryModel;
use App\Models\UserModel;
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
class TrainingType extends Entity
{
    protected $dates = ['created_at', 'updated_at'];

    public function formatDataTableModel()
    {
        helper('datatable_helper'); 
        return [
            'id' => $this->id,
            'name' => $this->name,
            'quota' => $this->quota,
            'quotaused' => $this->quota_used,
            'status' => statusRender($this->status),
            'is_jobseekers' => $this->is_jobseekers,
            'is_purna_pmi' => $this->is_purna_pmi,
            'group' => $this->getGroup(),
        ];
    }

    public function formatDataModel()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'quota' => $this->quota,
            'quota_used' => $this->quota_used,
            'status' => $this->status,
            'is_jobseekers' => $this->is_jobseekers,
            'is_purna_pmi' => $this->is_purna_pmi,
            'group' => $this->getGroup(),
        ];
    }

    public function formatRefDataModel()
    {
        return [
            'value' => $this->id,
            'label' => $this->name,
        ];
    }

    public function getCreator()
    {
        if (empty($this->attributes['created_by'])) {
            return null;
        }
        $userModel = new UserModel();
        return $userModel->withDeleted()->find($this->attributes['created_by']);
    }

    public function getUpdater()
    {
        if (empty($this->attributes['updated_by'])) {
            return null;
        }
        $userModel = new UserModel();
        return $userModel->withDeleted()->find($this->attributes['updated_by']);
    }

    public function getGroup()
    {
        $labels = [];

        if (!empty($this->attributes['is_purna_pmi'])) {
            $labels[] = 'Purna PMI';
        }

        if (!empty($this->attributes['is_jobseekers'])) {
            $labels[] = 'Job Seekers';
        }
        if (empty($labels)) {
            return null;
        }

        return implode(', ', $labels);
    }
}
