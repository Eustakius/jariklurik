<?php

namespace App\Models;

use App\Entities\Country;
use CodeIgniter\Model;

class CountryModel extends Model
{
    protected $table      = 'countries';
    protected $returnType = Country::class;
    protected $primaryKey = 'id';

    protected $allowedFields = [
                'id',
        'iso',
        'name',
        'nicename',
        'iso3',
        'numcode',
        'phonecode'
    ];
}