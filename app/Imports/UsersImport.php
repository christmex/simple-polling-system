<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new User([
            'name'                      => $row['name'],
            'email'                     => explode(' ',explode(',',$row['name'])[0])[0].''.Str::take($row['born_date'],2).''.Str::substr($row['born_date'], 3, 2).''.User::$domain,
            'password'                  => bcrypt('mantapjiwa00'),
            'born_place'                => $row['born_place'],
            'born_date'                 => !empty($row['born_date']) ? date('Y-m-d', strtotime(str_replace('/', '-', $row['born_date']))) : NULL,
            'citizenship_number'        => $row['citizenship_number'],
            'join_date'                 => !empty($row['join_date']) ? date('Y-m-d', strtotime(str_replace('/', '-', $row['join_date']))) : NULL,
            'finish_contract'           => !empty($row['finish_contract']) ? date('Y-m-d', strtotime(str_replace('/', '-', $row['finish_contract']))) : NULL,
            'notes'                     => $row['notes'],
        ]);
    }
}

