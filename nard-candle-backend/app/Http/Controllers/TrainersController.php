<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Trainee;
use App\Models\TrainingDay;
use Carbon\Carbon;

class TrainersController extends Controller
{
    public function index()
    {
        $trainees = Trainee::all();
        return view('admin.trainees', compact('trainees'));
    }
    // List all trainees
    // public function index()
    // {
    //     $trainees = Trainee::where('created_at', '>=', Carbon::now()->subDay())
    //         ->get();

    //     return view('trainers.index', compact('trainees'));
    // }

    // Show trainee details
    public function show($id)
    {
        $trainee = Trainee::findOrFail($id);
        return view('admin.trainees', compact('trainees'));
    }

    // Remove unpaid trainees after 24 hours
    public function removeUnpaid()
    {
        Trainee::where('status', 'waiting')
            ->where('created_at', '<', Carbon::now()->subDay())
            ->delete();

        return redirect()->route('admin.trainees.removeUnpaid');
    }

    // Manage available training days
    // public function trainingDays()
    // {
    //     $days = TrainingDay::all();
    //     return view('trainers.training-days.index', compact('days'));
    // }

    public function trainingDays()
{
    $days = TrainingDay::all();
    return view('admin.trainingDay', compact('days'));  // View path should match your structure
}

public function updateTrainingDays(Request $request)
{
    // Reset all training days to unavailable
    TrainingDay::query()->update(['is_available' => false]);

    // Update only the selected training days
    if ($request->has('days')) {
        TrainingDay::whereIn('id', $request->days)->update(['is_available' => true]);
    }

    return redirect()->back()->with('success', 'Training days updated successfully.');
}

}
