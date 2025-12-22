<?php

namespace App\Entities;

use App\Models\CountryModel;
use App\Models\TrainingTypeModel;
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
class PurnaPmi extends Entity
{
    public function formatDataTableModel()
    {
        helper('datatable_helper'); 
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'gender' => $this->gender == 'M' ? 'Male' : 'Female',
            'bod' => $this->birth_of_day,
            'educationlevel' => $this->education_level,
            'address' => $this->address,
            'end_year' => $this->end_year,
            'file' => fileRender($this->file),
            'training_type' => $this->training_type_name,
            'created_at'      => $this->created_at instanceof \DateTime
            ? $this->created_at->format('Y-m-d H:i:s')
            : (string) $this->created_at,
        ];
    }
    public function formatDataModel()
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'gender' => $this->gender == 'M' ? 'Male' : 'Female',
            'bod' => $this->birth_of_day,
            'educationlevel' => $this->education_level,
            'address' => $this->address,
            'end_year' => $this->end_year,
            'file' => $this->file,
            'training_type' => $this->training_type?->name,
            'created_at'      => $this->created_at instanceof \DateTime
            ? $this->created_at->format('Y-m-d H:i:s')
            : (string) $this->created_at,
        ];
    }

    public function getTrainingType()
    {
        $trainingTypeModel = new TrainingTypeModel();
        return $trainingTypeModel->withDeleted()->find($this->training_type_id);
    }
}
