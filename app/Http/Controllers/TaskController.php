<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\TaskResource;
use App\Mail\TaskApproved;
use App\Mail\TaskCompleted;

use App\Models\Task;
use App\Models\User;
use App\Models\Department;

use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail as FacadesMail;

class TaskController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       return TaskResource::collection(
        Task::where('user_id', Auth::user()->id)->get()
       );
    }

   

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $request -> validated($request->all());

        $task = Task::create([
            'user_id' => Auth::user()->id,
            'department_id' => Auth::user()->department_id,
            'name' => $request -> name,
            'description' => $request -> description,

        ]);

        return new TaskResource($task);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
{
    
    if(!$this->authorize('view', $task)){
        return $this ->error('', 'Only an Approver can perform this task', 403);
    };

    return new TaskResource($task);
}


   
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);
    
        if ($request->has('approved')) {
            $task->update($request->all());
    
            // Check if approved is true or 1
            if ($request->input('approved') === true || $request->input('approved') === 1) {
                $this->sendEmail($task, Auth::user()->name);
            }
    
            return new TaskResource($task);
        } else {
            $task->update($request->all());
    
            // Check if status is completed
            if ($request->has('status') && $request->input('status') == 'completed') {
                $this->sendEmail($task);
            }
    
            return new TaskResource($task);
        }
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        if (Auth::user()->id !== $task->user_id) {
            return $this->error('', 'You are not authorized to make this request', 403);
        }

        $task->delete();

        return response(null, 204);
    }


    private function sendEmail($task, $name = null){
        // Get the staff who owns the task
        $staff = $task->user;
        
        if ($name) {
        $approvalUrl = route('task.approved', ['task' => $task->id]);
            FacadesMail::to($staff->email)->send(new TaskApproved($task, $approvalUrl, $name, $staff));
    } 
       
      else { 
       // Get the list of approvers in the same department
       $department = Department::findOrFail($task->department_id);

       $approvers = $department->users()
           ->where('user_role', 'approver')
           ->get();
        // Send an email to each approver
        foreach ($approvers as $approver) {
            
            $approvalUrl = route('approve.task', ['task' => $task->id]);
            $redirectUrl = url('task/' . $task->id . '/approveTask');
            $approvalUrl = str_replace('http://%24url', $redirectUrl, $approvalUrl);
            FacadesMail::to($approver->email)->send(new TaskCompleted($task, $approvalUrl, $approver->name, $staff));
            
        }
    }
    }

    private function findApprover(){

    }
}
