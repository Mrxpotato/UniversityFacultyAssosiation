<?php

namespace App\Notifications;

use App\Models\Claim;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ClaimStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Claim $claim;

    protected string $action;

    protected ?string $reason;

    protected ?string $actorName;

    /**
     * Status action messages configuration
     */
    private const STATUS_CONFIG = [
        'created' => [
            'subject' => 'Claim Submitted Successfully',
            'greeting' => 'Claim Submitted!',
            'message' => 'Your claim has been submitted successfully and is now pending review.',
            'color' => 'success',
        ],
        'under_review' => [
            'subject' => 'Your Claim is Under Review',
            'greeting' => 'Claim Under Review',
            'message' => 'Your claim is now being verified by UFA through membership data and submitted evidence.',
            'color' => 'primary',
        ],
        'approved' => [
            'subject' => 'Your Claim Has Been Approved',
            'greeting' => 'Claim Approved!',
            'message' => 'Great news! Your claim has been approved and will proceed to billing & collection.',
            'color' => 'success',
        ],
        'billing' => [
            'subject' => 'Your Claim is in Billing & Collection',
            'greeting' => 'Billing Prepared',
            'message' => 'UFA has prepared the billing list for your claim. Collection is being arranged.',
            'color' => 'primary',
        ],
        'collecting' => [
            'subject' => 'Collection in Progress for Your Claim',
            'greeting' => 'Collection Monitoring',
            'message' => 'UFA is monitoring the receipt of collected funds via Campus Cashiers for your claim.',
            'color' => 'primary',
        ],
        'check_prepared' => [
            'subject' => 'Your Check Has Been Prepared',
            'greeting' => 'Check Ready!',
            'message' => 'Your check has been prepared and is ready for release. You will be notified when it is available for pick-up.',
            'color' => 'success',
        ],
        'released' => [
            'subject' => 'Your Claim Has Been Released',
            'greeting' => 'Claim Released!',
            'message' => 'Your claim check has been released. Please collect your check from the UFA office.',
            'color' => 'success',
        ],
        'rejected' => [
            'subject' => 'Your Claim Has Been Rejected',
            'greeting' => 'Claim Rejected',
            'message' => 'Unfortunately, your claim has been rejected.',
            'color' => 'error',
        ],
        'remanded' => [
            'subject' => 'Your Claim Requires Revision',
            'greeting' => 'Action Required',
            'message' => 'Your claim has been sent back for revision. Please review the feedback and update your claim.',
            'color' => 'warning',
        ],
        'billing_treasurer' => [
            'subject' => 'New Claim Ready for Billing',
            'greeting' => 'Billing Action Required',
            'message' => 'A claim has been approved and moved to billing. Please prepare the billing and collection for this claim.',
            'color' => 'primary',
        ],
    ];

    /**
     * Create a new notification instance.
     */
    public function __construct(Claim $claim, string $action, ?string $reason = null, ?string $actorName = null)
    {
        $this->claim = $claim;
        $this->action = $action;
        $this->reason = $reason;
        $this->actorName = $actorName;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $config = self::STATUS_CONFIG[$this->action] ?? [
            'subject' => 'Claim Status Update',
            'greeting' => 'Claim Update',
            'message' => 'Your claim status has been updated.',
            'color' => 'primary',
        ];

        $mail = (new MailMessage)
            ->subject($config['subject'].' - '.$this->claim->claim_number)
            ->greeting($config['greeting'])
            ->line($config['message'])
            ->line('**Claim Number:** '.$this->claim->claim_number)
            ->line('**Claim Type:** '.($this->claim->claimType->name ?? 'N/A'))
            ->line('**Amount:** ₱'.number_format($this->claim->amount, 2))
            ->line('**Current Status:** '.ucfirst(str_replace('_', ' ', $this->claim->status)));

        // Add reason if provided (for rejections and remands)
        if ($this->reason) {
            $reasonLabel = $this->action === 'rejected' ? 'Rejection Reason' : 'Feedback';
            $mail->line("**{$reasonLabel}:** ".$this->reason);
        }

        // Add actor name if provided
        if ($this->actorName) {
            $mail->line('**Processed by:** '.$this->actorName);
        }

        // Add appropriate action button
        $mail->action('View Claim Details', route('claims.show', $this->claim));

        // Add closing line based on status
        if ($this->action === 'remanded') {
            $mail->line('Please log in to update your claim with the requested changes.');
        } elseif ($this->action === 'rejected') {
            $mail->line('If you have questions about this decision, please contact the association office.');
        } else {
            $mail->line('Thank you for using the University Faculty Association system.');
        }

        return $mail;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'claim_id' => $this->claim->id,
            'claim_number' => $this->claim->claim_number,
            'action' => $this->action,
            'status' => $this->claim->status,
            'reason' => $this->reason,
            'actor_name' => $this->actorName,
        ];
    }
}
