<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Teacher;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::user()->role_id == 1) {
            return view('dashboard.index', [
                'teachers' => Teacher::filter(request(['daerah', 'category']))->get(),
                'category' => Category::get(),
            ]);
        } else if (Auth::user()->role_id == 2) {
            return view('dashboard.index');
        }
    }
    public function show($id)
    {
        $teacher = Teacher::where('id', $id)->first();
        $student = Student::where('user_id', Auth::user()->id)->first();
        $order = Order::where('student_id', $student->id)
            ->where('teacher_id', $id)
            ->where('status_order', null)
            ->orWhere('status_order', false)
            ->latest()->first();
        return view('dashboard.detail-teacher', [
            't' => $teacher,
            'order' => $order,
        ]);
    }
}
