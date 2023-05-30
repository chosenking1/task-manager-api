<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Resources\TaskResource;
use App\Mail\TaskApproved;
use App\Mail\TaskCompleted;

use App\Models\Task;
use App\Models\User;


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
        return $this ->error('', 'You are not Authorised can perform this task', 403);
    };

    return new TaskResource($task);
}


   
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        
        $this->authorize('update', $task, );
        $user = Auth::user();

    if ($request->has('approved')) {
            
    if ($request->approved == true || $request->approved == 1) {
                $this->sendEmailNotification($task, Auth::user()->name);
            }
                $task->update($request->all());
                return new TaskResource($task);
        } 
        
            if ($request->has('status') && $request->status == 'completed') {
                $this->sendEmailNotification($task);
            }
            
            $task->update($request->all());
            return new TaskResource($task);
        
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


    private function sendEmailNotification($task, $name = null){
        $staff = $task->user;
        
        if ($name) {
        $approvalUrl = route('task.approved', ['task' => $task->id]);
            FacadesMail::to($staff->email)->send(new TaskApproved($task, $approvalUrl, $name, $staff));
    } 
       
      else { 
            $approvers = User::where('department_id', $task->department_id)
            ->where('user_role', 'approver')
            ->get();

      
        foreach ($approvers as $approver) {
            
            $approvalUrl = route('approve.task', ['task' => $task->id]);
            $redirectUrl = url('task/' . $task->id . '/approveTask');
            $approvalUrl = str_replace('http://%24url', $redirectUrl, $approvalUrl);
            FacadesMail::to($approver->email)->send(new TaskCompleted($task, $approvalUrl, $approver->name, $staff));
            
        }
    }
    }

    
}
