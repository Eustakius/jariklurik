<?php

namespace App\Entities;

use App\Models\CompanyModel;
use App\Models\CountryModel;
use App\Models\TrainingTypeModel;
use CodeIgniter\Entity\Entity;
use CodeIgniter\I18n\Time;

/**
 * Group Entity
 *
 * As of version 1.2 this class is used by the new GroupModel
 * to allow using a strongly-typed return. Any logic in this
 * class should not be relied on within this library.
 *
 * @since 1.2.0
 */
class JobSeeker extends Entity
{
    protected $dates = ['created_at', 'updated_at'];
    
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
            'file_statement' => fileRender($this->file_statement),
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
            'file_statement' => $this->file_statement,
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
