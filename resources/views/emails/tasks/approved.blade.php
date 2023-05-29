<x-mail::message>
    
        # Task Approval Notification
    
        Hi {{ $staff }},
    
        Your task "{{ $task_name }}" has been approved as completed. Here are the details:
    
        - Description: {{ $task_description }}
        - Status: {{ $task_status }}
        - Created At: {{ $task_created }}
        - Updated At: {{ $task_updated }}
    
        You can view the details and provide any additional comments by clicking the button below:
    
        <x-mail::button :url="$url">
            View Task
        </x-mail::button>
    
        If you have any questions or need further assistance, please let us know.
    
        Thank you,
        {{ config('app.name') }}
    </x-mail::message>
    
