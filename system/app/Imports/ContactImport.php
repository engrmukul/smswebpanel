<?php

namespace App\Imports;

use App\Models\Contact;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Carbon\Carbon;
use Illuminate\Support\Str;

class ContactImport implements ToModel, WithValidation, WithHeadingRow, WithChunkReading, ShouldQueue, WithBatchInserts
{
    use Importable;
    protected $attributes = [];
    public $timeout = 120;

    public function __construct($attributes = [])
    {
        $this->attributes = $attributes;
        ini_set('memory_limit', '-1');
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Contact([
            'name_en'  => Str::limit($row['name_en'], 250),
            'name_bn' => Str::limit($row['name_bn'], 250),
            'phone' => $row['phone'],
            'email' => $row['email'],
            'profession' => $row['profession'],
            'gender' => $this->format_gender($row['gender']),
            'dob' => $this->format_dob($row['dob']),
            'division' => $row['division'],
            'district' => $row['district'],
            'upazilla' => $row['upazilla'],
            'blood_group' => $row['blood_group']
        ] + $this->attributes);
    }

    public function format_dob($dob): string
    {
        if (!$dob) {
            return Carbon::now()->format('Y-m-d');
        } else if (strlen($dob) == 4) {
            $date = Carbon::createFromDate($dob, 1, 1);
            return  $date->copy()->startOfYear()->format('Y-m-d');
        } else {
            return Carbon::parse($dob)->format('Y-m-d');
        }
    }

    public function format_gender($gender): string
    {
        if(!$gender || !in_array(strtolower($gender), ["male", "female", "f", "m"])) {
            return "Other";
        } else if (strtolower($gender)=='m') {
            return "Male";
        } else if (strtolower($gender) == 'f') {
            return "Female";
        } else {
            return $gender;
        }
    }

    public function format_location($location): string
    {
        if ($location) {
            $loc_string = explode(',', $location);
            return array_shift($loc_string);
        }
        return '';
    }

    public function rules(): array
    {
        return [
            'name_en'  => 'required'
        ];
    }

    public function chunkSize(): int
    {
        return 1000;
    }
    public function batchSize(): int
    {
        return 500;
    }
}
