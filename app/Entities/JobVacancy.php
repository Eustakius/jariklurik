<?php

namespace App\Entities;

use App\Models\CompanyModel;
use App\Models\CountryModel;
use App\Models\UserModel;
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
class JobVacancy extends Entity
{
    
    public function formatDataTableModel()
    {
        helper('datatable_helper'); 
        
        return [
            'id' => $this->id,
            'code' => $this->code,
            'position' => $this->position,
            'selection_date' => $this->selection_date,
            'status' => statusRender($this->status),
            'visitor' => $this->visitor,
            'applicant' => $this->applicant,
            'applicant_process' => $this->applicant_process,
            'quota' => $this->male_quota + $this->female_quota + $this->unisex_quota,
            'quota_used' => $this->unisex_quota_used + $this->male_quota_used + $this->female_quota_used,
            'company' => $this->company_name,
            'country' => $this->country_name,
            'duration' => $this->duration . ' ' . $this->duration_type,
            'is_pin' => checkboxRenderFlag($this->is_pin, $this->id, "", "/back-end/api/job-vacancy/data-table-update"),
        ];
    }

    public function formatDataModel()
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'position' => $this->position,
            'selection_date' => $this->selection_date,
            'status' => $this->status,
            'visitor' => $this->visitor,
            'applicant' => $this->applicant,
            'applicant_process' => $this->applicant_process,
            'quota' => $this->quota,
            'quota_used' => $this->quota_used,
            'company' => $this->company?->name,
            'country' => $this->country?->name,
            'duration' => $this->duration . ' ' . $this->duration_type,
        ];
    }

    public function formatDataFrontendModel()
    {
        return [
            'slug' => slugify($this->position . '-' . $this->country?->name . '-' . $this->company?->name . '-' . shortEncrypt($this->id)),
            'position' => $this->position,
            'company' => (object)[
                'name' => $this->company?->name,
                'logo' => !empty($this->company?->logo) 
                            ? base_url(ltrim($this->company->logo, '/')) 
                            : base_url('image/logo.png'),
            ],
            'country' => $this->country?->name,
            'duration' => $this->duration . ' ' . $this->duration_type,
            'pin' => $this->is_pin
        ];
    }

    public function formatDataFrontendDetailModel()
    {
        return [
            'slug' => slugify($this->position . '-' . $this->country?->name . '-' . $this->country?->name . '-' . shortEncrypt($this->id)),
            'position' => $this->position,
            'email' => $this->email,
            'description' => $this->description,
            'requirement' => $this->requirement,
            'malequota' => $this->male_quota - $this->male_quota_used,
            'femalequota' => $this->female_quota - $this->female_quota_used,
            'unisexquota' => $this->unisex_quota  - $this->unisex_quota_used,
            'requirement' => $this->requirement,
            'selection' => Time::createFromFormat('Y-m-d', $this->selection_date)->toLocalizedString('dd MMM yyyy'),
            'company' => (object)[
                'name' => $this->company?->name,
                'logo' => $this->company?->logo,
                'about' => $this->company?->about,
                'address' => $this->company?->address,
                'sector' => $this->company?->business_sector,
                'phone' => $this->company?->phone,
            ],
            'country' => $this->country?->name,
            'duration' => $this->duration . ' ' . $this->duration_type,
        ];
    }

    public function getCompany()
    {
        $companyModel = new CompanyModel();
        return $companyModel->withDeleted()->find($this->company_id);
    }

    public function getCountry()
    {
        $countryModel = new CountryModel();
        return $countryModel->withDeleted()->find($this->country_id);
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
}
