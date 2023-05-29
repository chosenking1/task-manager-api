<x-mail::message>
# Task Completed

Hello {{ $approver }},

This task "{{ $task_name}}" has been completed.
Here are the details
{{ $task_description }}
{{ $task_status}}
{{ $task_created }}
{{ $task_updated }}
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
