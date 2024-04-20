<?php 
namespace App\Http\Controllers;

use App\Models\tickets;
use App\Models\ticketItems;
use App\Models\Sales;
use App\Models\Product;
use App\Models\User;
use App\Models\Task;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class CoreController
 * @package App\Http\Controllers
 * 
 * In this controller I will manage everything related 
 * to tasks, users, boxfound and events.
 */
class CoreController extends Controller
{
    
    /**
     * Get all active users
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getActiveUsers()
    {
        $users = User::where('is_active', 1)->get();
        return response()->json($users);
    }

    public function getSingleTask($name)
    {
        $task = Task::where('name', $name)->first();
        return response()->json($task);
    }

    /**
     * Get all tasks
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTasks($recordType)
    {
        
        $tasks = Task::where([
            ['assigned_to_id', '=', ($recordType == 'bills') ? 100000 : Auth::id()],
            ['record_type', $recordType]
        ])->get();

        for ($i=0; $i < count($tasks); $i++) { 
            $tasks[$i]->readOnly = !User::find(Auth::id())->is_admin;
        }

        return response()->json($tasks);
    }

    public function  getAnotherTasks($recordType)  {
        $tasks = Task::where([
            ['assigned_to_id', '!=', Auth::id()],
            ['record_type', '=', $recordType] 
        ])->get();
        return response()->json($tasks);
    }

    public function createTask(Request $request)
    {
        $task = new Task();
        $task->name = $request->name;
        $task->assigned_to_id = $request->assigned_to;
        $task->status = 'open';
        $task->created_by_id = Auth::id();
        $task->edited_by_id = Auth::id();
        $task->expiry_date = Carbon::today()->addDays(10);
        $task->record_type = $request->recordType;
        if($request->assigned_to == Auth::id())
            $task->description = 'my task';
        else{
            $task->description = 'task for ' . User::find($request->assigned_to)->name . ' Created by' . Auth::user()->name;
        }

        $task->save();
        return response()->json($task);
    }


    public function editTask(Request $request)
    {
        $task = Task::where('name', $request->task)->first();
        $task->status = $request->status;
        $task->edited_by_id = Auth::id();
        $task->description = 'task ' . $request->status . ' by ' . Auth::user()->name;
        if($request->status == 'completed' || $request->status == 'cancelled')
            $task->expiry_date = Carbon::today();
        else
            $task->expiry_date = Carbon::today()->addDays(10);

        $task->save();
        return response()->json($task);
    }

    public function deleteTask(Request $request)
    {
        $task = Task::where('name', $request->task)->first();
        
        if(User::find(Auth::id() )->is_admin){
            $task->delete();
            return response()->json([$task, User::find(Auth::id() )->is_admin]);
        }else
            return response()->json([
                'message' => 'You are not allowed to delete this task',
                'status' => 'error',
                'code' => 401,
                'error' => 'Unauthorized'
            ]);

        
    }

}