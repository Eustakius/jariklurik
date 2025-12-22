<?php

namespace App\Libraries;

use App\Models\SettingModel;
use App\Models\TrainingTypeModel;
use Config\JWT as JWTConfig;

class ModelService
{
    protected $config;

    public function __construct()
    {
        $this->config = new JWTConfig();
    }

    public function dataMaster($key, $slug): array
    {
        if ($key == 'training_type') {
            $model = new TrainingTypeModel();
            $model->where('quota > quota_used');
            if($slug == "daftar-kepelatihan" || $slug == "daftar-kepelatihan-pencari-kerja"){

                $models = $model->where("status", 1)->where("is_jobseekers", 1)->findAll();
            }
            else if($slug == "daftar-kepelatihan-purna-pmi"){
                $models = $model->where("status", 1)->where("is_purna_pmi", 1)->findAll();

            }

            return array_map(function ($item) {
                return $item->formatRefDataModel();
            }, $models);
        }
        else if ($key == 'file_sample') {
            $model = new SettingModel();
            $setting = $model->where('key', 'file_statement_letter')->first();
            return $setting ? $setting->toArray() : [];
        }

        return [];
    }
}
