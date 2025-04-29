<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Flasher\Toastr\Laravel\Facade\Toastr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ImportDataService
{
    /**
     * Map department text to its corresponding department ID.
     *
     * @param string $departmentText
     * @return int|null
     */
    private function mapDepartment($departmentText)
    {
        return match ($departmentText) {
            'RPL' => 1,
            'DPIB' => 2,
            'K3R' => 3,
            default => null
        };
    }

    /**
     * Map gender text to the corresponding gender value.
     *
     * @param string $genderText
     * @return string
     */
    private function mapGender($genderText)
    {
        return $genderText === 'Laki-Laki' ? 'men' : 'women';
    }

    /**
     * Map position text to the corresponding position ID.
     *
     * @param string $position
     * @return int|null
     */
    private function mapPosition($position)
    {
        return match ($position) {
            'Guru Pertama' => 1,
            'Guru Muda' => 2,
            'Guru Madya' => 3,
            'Guru Utama' => 4,
            default => null,
        };
    }

    /**
     * Map level text to the corresponding level ID.
     *
     * @param string $level
     * @return int|null
     */
    private function mapLevel($level)
    {
        return match ($level) {
            'I/a' => 1,
            'I/b' => 2,
            'I/c' => 3,
            'I/d' => 4,
            'II/a' => 5,
            'II/b' => 6,
            'II/c' => 7,
            'II/d' => 8,
            'III/a' => 9,
            'III/b' => 10,
            'III/c' => 11,
            'III/d' => 12,
            'IV/a' => 13,
            'IV/b' => 14,
            'IV/c' => 15,
            'IV/d' => 16,
            'IV/e' => 17,
            default => null,
        };
    }

    /**
     * Validate and import advisor data from an uploaded Excel file.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function importAdvisorDataCheck($file)
    {
        $spreadsheet = IOFactory::load($file->getPathname()); // Load Excel file
        $worksheet = $spreadsheet->getSheetByName('Data'); // Get the specific sheet
        $rows = $worksheet->toArray(); // Convert sheet data to array

        $validData = [];
        $errors = [];

        // Process each row of data
        foreach ($rows as $index => $row) {
            if ($index == 0) continue; // Skip header row
            if (collect($row)->filter()->isEmpty()) {
                break; // Stop if the row is empty
            }

            $data = [
                'name' => $row[1],
                'nip' => $row[2],
                'department_id' => $row[3],
                'position_id' => $row[4],
                'level_id' => $row[5],
                'username' => $row[6],
                'email' => $row[7],
                'phone_num' => $row[8],
                'password' => $row[9],
            ];

            // Define validation rules
            $rules = [
                'name' => 'required|string',
                'nip' => 'required|size:18|unique:advisors,nip',
                'department_id' => 'required',
                'position_id' => 'required',
                'level_id' => 'required',
                'username' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'phone_num' => 'required|string|min:10|max:14|unique:advisors,phone_num',
                'password' => 'required|string|min:7|max:12',
            ];

            $validator = Validator::make($data, $rules); // Perform validation

            // Check if validation fails
            if ($validator->fails()) {
                $errors[] = [
                    'row' => $index + 1,
                    'messages' => $validator->errors()->all()
                ];
            } else {
                // Map data fields and prepare for import
                $data['department_id'] = $this->mapDepartment($data['department_id']);
                $data['position_id'] = $this->mapPosition($data['position_id']);
                $data['level_id'] = $this->mapLevel($data['level_id']);
                $data['status'] = '1';
                $validData[] = $data;
            }
        }

        // If errors exist, return them in the response
        if (!empty($errors)) {
            $pesan = "Validasi gagal pada baris:\n";
            foreach ($errors as $error) {
                $pesan .= "Baris {$error['row']}: " . implode(', ', $error['messages']) . "\n";
            }
            Toastr::addError(nl2br($pesan));
            throw ValidationException::withMessages([
                'message' => $pesan,
            ]);
        }

        return $validData; // Return validated data
    }

    /**
     * Validate and import student data from an uploaded Excel file.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function importStudentDataCheck($file)
    {
        $spreadsheet = IOFactory::load($file->getPathname()); // Load Excel file
        $worksheet = $spreadsheet->getSheetByName('Data'); // Get the specific sheet
        $rows = $worksheet->toArray(); // Convert sheet data to array

        $validData = [];
        $errors = [];

        // Process each row of data
        foreach ($rows as $index => $row) {
            if ($index == 0) continue; // Skip header row
            if (collect($row)->filter()->isEmpty()) {
                break; // Stop if the row is empty
            }

            $data = [
                'name' => $row[1],
                'nisn' => $row[2],
                'nis' => $row[3],
                'gender' => $row[4],
                'department_id' => $row[5],
                'year' => $row[6],
                'username' => $row[7],
                'email' => $row[8],
                'phone_num' => $row[9],
                'password' => $row[10],
            ];

            // Define validation rules
            $rules = [
                'name' => 'required|string',
                'nisn' => 'required|string|size:10|unique:students,nisn',
                'nis' => 'required|string|size:4|unique:students,nis',
                'gender' => 'required',
                'department_id' => 'required',
                'year' => 'required',
                'username' => 'required',
                'email' => 'required|unique:users,email',
                'phone_num' => 'required|string|min:10|max:14|unique:students,phone_num',
                'password' => 'required|string|min:8|max:12',
            ];

            $validator = Validator::make($data, $rules); // Perform validation

            // Check if validation fails
            if ($validator->fails()) {
                $errors[] = [
                    'row' => $index + 1,
                    'messages' => $validator->errors()->all()
                ];
            } else {
                // Map data fields and prepare for import
                $data['gender'] = $this->mapGender($data['gender']);
                $data['department_id'] = $this->mapDepartment($data['department_id']);
                $data['status'] = '1';
                $validData[] = $data;
            }
        }

        // If errors exist, return them in the response
        if (!empty($errors)) {
            $pesan = "Validasi gagal pada baris:\n";
            foreach ($errors as $error) {
                $pesan .= "Baris {$error['row']}: " . implode(', ', $error['messages']) . "\n";
            }
            Toastr::addError(nl2br($pesan));
            throw ValidationException::withMessages([
                'message' => $pesan,
            ]);
        }

        return $validData; // Return validated data
    }

    /**
     * Validate and import industry data from an uploaded Excel file.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @return array
     * @throws \Illuminate\Validation\ValidationException
     */
    public function importIndustryDataCheck($file)
    {
        $spreadsheet = IOFactory::load($file->getPathname()); // Load Excel file
        $worksheet = $spreadsheet->getSheetByName('Data'); // Get the specific sheet
        $rows = $worksheet->toArray(); // Convert sheet data to array

        $validData = [];
        $errors = [];

        // Process each row of data
        foreach ($rows as $index => $row) {
            if ($index == 0) continue; // Skip header row
            if (collect($row)->filter()->isEmpty()) {
                break; // Stop if the row is empty
            }

            $data = [
                'name' => $row[1],
                'address' => $row[2],
                'email' => $row[3],
                'phone_num' => $row[4],
                'leader_name' => $row[5],
            ];

            // Define validation rules
            $rules = [
                'name' => 'required|string',
                'address' => 'required|string',
                'email' => 'required|unique:industries,email|email',
                'phone_num' => 'required|unique:industries,phone_num|string|min:10|max:14',
                'leader_name' => 'required|string',
            ];

            $validator = Validator::make($data, $rules); // Perform validation

            // Check if validation fails
            if ($validator->fails()) {
                $errors[] = [
                    'row' => $index + 1,
                    'messages' => $validator->errors()->all()
                ];
            } else {
                // Prepare data for import
                $data['status'] = '1';
                $validData[] = $data;
            }
        }

        // If errors exist, return them in the response
        if (!empty($errors)) {
            $pesan = "Validasi gagal pada baris:\n";
            foreach ($errors as $error) {
                $pesan .= "Baris {$error['row']}: " . implode(', ', $error['messages']) . "\n";
            }
            Toastr::addError(nl2br($pesan));
            throw ValidationException::withMessages([
                'message' => $pesan,
            ]);
        }

        return $validData; // Return validated data
    }
}
