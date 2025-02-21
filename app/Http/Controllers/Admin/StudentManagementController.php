<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Services\BatchService;
use App\Services\StudentService;
use App\Services\DepartmentService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class StudentManagementController extends Controller
{
    protected $studentService,
        $batchService,
        $departmentService,
        $userService;

    // Constructor Injection
    public function __construct(
        StudentService $studentService,
        BatchService $batchService,
        DepartmentService $departmentService,
        UserService $userService
    ) {
        $this->studentService = $studentService;
        $this->batchService = $batchService;
        $this->departmentService = $departmentService;
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        $currentBatch = $this->batchService->getBatchByStatus('active');
        $batch_id = $request->batch ?? ($currentBatch->id ?? '');
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
            'search' => $request->searchKeyword ?? '',
        ];

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
        if ($request->check_password !== $request->password) {
            return back()->withErrors(['password' => 'Passwords do not match.']);
        }
        $data = $request->except(['_token']);
        $validatedData = $request->validate([
            'name' => 'required|string',
            'nisn' => 'required|size:10|unique:students,nisn',
            'gender' => 'required',
            'department_id' => 'required',
            'year' => 'required',
            'username' => 'required',
            'email' => 'required|unique:users,email',
            'phone_num' => 'required|string|min:10|max:14|unique:students,phone_num,',
            'password' => 'required|string|size:8',
        ]);

        $validatedData['password'] = Hash::make($validatedData['password']);
        $validatedData['department_id'] = $validatedData['department_id'] == 'K3R' ? '1' : ($validatedData['department_id'] == 'DPIB' ? '2' : ($validatedData['department_id'] == 'RPL' ? '3' : ''));
        $validatedData['gender'] = $validatedData['gender'] == 'Laki-Laki' ? 'men' : 'women';

        $newUser = $this->userService->addUser([
            'username' => $validatedData['username'],
            'email' => $validatedData['email'],
            'password' => $validatedData['password'],
            'role' => 'student',
        ]);
        $this->studentService->addStudent([
            'user_id' => $newUser->id,
            'name' => $validatedData['name'],
            'nisn' => $validatedData['nisn'],
            'gender' => $validatedData['gender'],
            'department_id' => $validatedData['department_id'],
            'year' => $validatedData['year'],
            'phone_num' => $validatedData['phone_num'],
        ]);

        return redirect()->back();

        // try {
        //     $this->studentService->addStudent($validatedData);
        //     return redirect()->route('studentManagement')->with('success', 'student added successfully.');
        // } catch (\Exception $e) {
        //     // \Log::error($e->getMessage());
        //     return back()->withErrors(['error' => 'Failed to add student.']);
        // }
    }

    public function update(Request $request, $id)
    {
        $data = $request->except(['_token', '_method']);

        $validatedData = $request->validate([
            'user_id' => 'required',
            'name' => 'required|string',
            'nisn' => 'required|size:10|unique:students,nisn,' . $id,
            'gender' => 'required',
            'department_id' => 'required',
            'year' => 'required',
            'username' => 'required',
            'email' => 'required|unique:users,email,' . $request->input('user_id'),
            'phone_num' => 'required|string|min:10|max:14|unique:students,phone_num,' . $id,
            'password' => 'nullable|string|size:8',
        ]);

        if (!empty($validatedData['password'])) {
            if ($request->check_password !== $request->password) {
                return back()->withErrors(['password' => 'Passwords do not match.']);
            }
            $validatedData['password'] = Hash::make($validatedData['password']);
        }

        $validatedData['department_id'] = $validatedData['department_id'] == 'K3R' ? '1' : ($validatedData['department_id'] == 'DPIB' ? '2' : ($validatedData['department_id'] == 'RPL' ? '3' : ''));
        $validatedData['gender'] = $validatedData['gender'] == 'Laki-Laki' ? 'men' : 'women';

        $this->studentService->updateStudent($id, [
            'name' => $validatedData['name'],
            'nisn' => $validatedData['nisn'],
            'gender' => $validatedData['gender'],
            'department_id' => $validatedData['department_id'],
            'year' => $validatedData['year'],
            'phone_num' => $validatedData['phone_num'],
        ]);

        $updateUserData = [
            'username' => $validatedData['username'],
            'email' => $validatedData['email'],
        ];

        if (isset($validatedData['password'])) {
            $updateUserData['password'] = $validatedData['password'];
        }

        $this->userService->updateUser($validatedData['user_id'], $updateUserData);

        return back();
    }

    public function destroy($id)
    {
        $user_id = $this->studentService->getStudentById($id)->user_id;
        $this->userService->deleteUser($user_id);
        return redirect()->back();
        // try {
        //     $this->studentService->deleteStudent($id);
        //     return redirect()->route('studentManagement')->with('success', 'student deleted successfully.');
        // } catch (\Exception $e) {
        //     // \Log::error($e->getMessage());
        //     return back()->withErrors(['error' => 'Failed to delete student.']);
        // }
    }
}
