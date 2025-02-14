<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\BatchService;
use App\Services\StudentService;
use App\Services\DepartmentService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class StudentManagementController extends Controller
{
    protected $studentService, $batchService, $departmentService;

    // Constructor Injection
    public function __construct(StudentService $studentService, BatchService $batchService, DepartmentService $departmentService)
    {
        $this->studentService = $studentService;
        $this->batchService = $batchService;
        $this->departmentService = $departmentService;
    }

    public function index(Request $request)
    {
        // batch data
        $currentBatch = $this->batchService->getBatchByStatus('active');
        // dd($currentBatch->id);
        $batch_id = $request->batch ?? ($currentBatch->id ?? '');
        // dd($batch_id);
        $year = $request->year ?? ($currentBatch->year ?? '');

        // table filters
        $departmentData = $this->departmentService->getAllDepartment();
        $batchData = $this->batchService->getAllBatch('');
        $yearData = $this->studentService->getStudentYear();
        $filters = [
            'year' => $year,
            'batch_id' => $batch_id,
            'department' => $request->department ?? '',
            'status' => $request->status ?? '',
            // 'status' => $request->status ? ($request->status == 'Aktif' ? 'active' : 'inactive') : '',
            'search' => $request->searchKeyword ?? '',
        ];
        // dd($filters);

        // table data
        $data = $this->studentService->getStudent($filters);

        // card data
        $registeredStudent = $this->studentService->getStudentByStatusCount($year, $batch_id, 'registered');
        $unregisteredStudent = $this->studentService->getStudentByStatusCount($year, $batch_id, 'unregistered');

        return view('pages.admin.student', [
            'data' => $data,
            'registeredStudent' => $registeredStudent,
            'unregisteredStudent' => $unregisteredStudent,
            'batchData' => $batchData,
            'yearData' => $yearData,
            'departmentData' => $departmentData,
            'filters' => $filters,
        ]);
    }

    public function store(Request $request)
    {
        // dd($request->all());
        if ($request->check_password !== $request->password) {
            return back()->withErrors(['password' => 'Passwords do not match.']);
        }
        $data = $request->except(['_token']);
        // dd($request->all());
        $validatedData = $request->validate([
            'name' => 'required|string',
            'nisn' => 'required|size:10|unique:students,nisn',
            'gender' => 'required',
            'department_id' => 'required',
            'year' => 'required',
            'phone_num' => 'required|string|min:10|max:14|unique:students,phone_num,',
            'password' => 'required|string|size:8',
        ]);
        // dd($validatedData);
        $validatedData['password'] = Hash::make($validatedData['password']);
        $validatedData['department_id'] = $validatedData['department_id'] == 'K3R' ? '1' : ($validatedData['department_id'] == 'DPIB' ? '2' : ($validatedData['department_id'] == 'RPL' ? '3' : ''));
        $validatedData['gender'] = $validatedData['gender'] == 'Laki-Laki' ? 'men' : 'women';

        // dd($validatedData);

        try {
            $this->studentService->addStudent($validatedData);
            return redirect()->route('studentManagement')->with('success', 'student added successfully.');
        } catch (\Exception $e) {
            // \Log::error($e->getMessage());
            return back()->withErrors(['error' => 'Failed to add student.']);
        }
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        // dd($id);
        if ($request->check_password !== $request->password) {
            return back()->withErrors(['password' => 'Passwords do not match.']);
        }
        $data = $request->except(['_token', '_method']);
        // dd($request->all());
        // dd($data);
        $validatedData = $request->validate([
            'name' => 'required|string',
            'nisn' => 'required|size:10|unique:students,nisn,' . $id,
            'gender' => 'required',
            'department_id' => 'required',
            'year' => 'required',
            'phone_num' => 'required|string|min:10|max:14|unique:students,phone_num,' . $id,
            'password' => 'required|string|size:8',
        ]);
        $validatedData['password'] = Hash::make($validatedData['password']);
        // dd($validatedData);
        $validatedData['department_id'] = $validatedData['department_id'] == 'K3R' ? '1' : ($validatedData['department_id'] == 'DPIB' ? '2' : ($validatedData['department_id'] == 'RPL' ? '3' : ''));
        $validatedData['gender'] = $validatedData['gender'] == 'Laki-Laki' ? 'men' : 'women';

        // dd($validatedData);
        try {
            $this->studentService->updateStudent($id, $validatedData);
            return redirect()->route('studentManagement')->with('success', 'student added successfully.');
        } catch (\Exception $e) {
            // \Log::error($e->getMessage());
            return back()->withErrors(['error' => 'Failed to add student.']);
        }
    }

    public function destroy($id)
    {
        try {
            $this->studentService->deleteStudent($id);
            return redirect()->route('studentManagement')->with('success', 'student deleted successfully.');
        } catch (\Exception $e) {
            // \Log::error($e->getMessage());
            return back()->withErrors(['error' => 'Failed to delete student.']);
        }
    }
}
