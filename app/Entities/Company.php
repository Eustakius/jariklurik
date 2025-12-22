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
class Company extends Entity
{
    protected $dates = ['created_at', 'updated_at'];

    public function formatDataTableModel()
    {
        helper('datatable_helper'); 
        
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'business_sector' => $this->business_sector,
            'logo' => imgRender($this->logo),
            'npwp' => $this->npwp,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'about' => $this->about,
            'user_id' => $this->user_id,
            'status' => statusRender($this->status),
        ];
    }

    public function formatDataModel()
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'business_sector' => $this->business_sector,
            'logo' => $this->logo,
            'npwp' => $this->npwp,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'about' => $this->about,
            'user_id' => $this->user_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            'created_by' => $this->getCreator()?->username ?? null,
            'updated_by' => $this->getUpdater()?->username ?? null,
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
    
    public function getUser()
    {
        if (empty($this->attributes['user_id'])) {
            return null;
        }
        $userModel = new UserModel();
        return $userModel->withDeleted()->find($this->attributes['user_id'])->username;
    }

}
