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
class Applicant extends Entity
{
    protected $dates = ['created_at', 'updated_at'];
    
    public function formatDataTableModel()
    {
        helper('datatable_helper'); 
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->first_name . (!empty($this->last_name) ? ' ' . $this->last_name : ''),
            'email' => $this->email,
            'phone' => $this->phone,
            'gender' => $this->gender == 'M' ? 'Male' : 'Female',
            'bod' => $this->birth_of_day,
            'educationlevel' => $this->education_level,
            'file' => fileRender($this->file_cv),
            'job_vacancy' => $this->job_vacancy_name,
            'company' => $this->company_name,
            'country' => $this->country_name,
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'gender' => $this->gender == 'M' ? 'Male' : 'Female',
            'bod' => $this->birth_of_day,
            'educationlevel' => $this->education_level,
            'file_cv' => $this->file_cv,
            'job_vacancy' => $this->job_vacancy_name,
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
