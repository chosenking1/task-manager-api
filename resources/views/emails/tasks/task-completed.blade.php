<x-mail::message>
# Task Completed

Hello {{ $approver }},

This task "{{ $task_name}}" has been completed.
Here are the details
- Description: {{ $task_description }}
- Status: {{ $task_status}}
- Created At: {{ $task_created }}
- Updated At: {{ $task_updated }}
submited complete by {{ $username }}

{{-- <x-mail::table>
| Laravel       | Table         | Example  |
| ------------- |:-------------:| --------:|
| Col 2 is      | Centered      | $10      |
| Col 3 is      | Right-Aligned | $20      |
</x-mail::table> --}}

<x-mail::button :url="$url">
Button Text
</x-mail::button>

Thanks,<br>
Your Company
{{ config('app.name') }}
</x-mail::message>
