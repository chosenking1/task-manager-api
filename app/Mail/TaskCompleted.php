<?php

namespace App\Mail;

use App\Models\Task;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TaskCompleted extends Mailable
{
    use Queueable, SerializesModels;

    // public $staff;
    // public $task;
    // public $approvalUrl;
    /**
     * Create a new message instance.
     */
    public function __construct(protected Task $task,protected $approvalUrl, protected  $approver, protected User $staff)
    {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Task Completed',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
       
        return new Content(
            markdown: 'emails.tasks.task-completed',
            with: [
                'approver' => $this-> approver,
                'username' => $this-> staff -> name,
                 'task_name' => $this->task->name,
                'task_description' => $this->task->description,
                'task_status' => $this->task->status,
                'task_created' => $this->task->created_at,
                'task_updated' => $this->task->updated_at,
                'url' => $this-> approvalUrl,
    
            ],

        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
