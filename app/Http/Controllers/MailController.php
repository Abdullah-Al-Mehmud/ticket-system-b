<?php

namespace App\Http\Controllers;

use App\Mail\SendMail;
use App\Models\Ticket;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class MailController extends Controller
{
    public function send()
    {
        try {
            Mail::to('test@gmail.com')->send(
                new SendMail(
                    'Test Subject',
                    'Hello! This is a test email.',
                    false
                )
            );

            return response()->json([
                'status' => true,
                'message' => 'Mail sent successfully',
            ], 200);
        } catch (\Exception $e) {
            Log::error('Test email failed: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to send test email.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function sendBookingEmail(Request $request)
    {
        $request->validate([
            'ticket_id' => 'required|array',
            'ticket_id.*' => 'exists:tickets,id',
        ]);

        $ticketIds = $request->ticket_id;

        try {
            $tickets = Ticket::with([
                'ticketCategory:id,event_id,name,price',
                'ticketCategory.event:id,title,location,start_date,end_date,category_id',
                'ticketCategory.event.category:id,name',
                'user:id,name,email',
            ])->findOrFail($ticketIds); // This accepts array

            if ($tickets->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No valid tickets found.',
                ], 404);
            }
            $groupedTickets = $tickets->groupBy('user.email');

            $sentCount = 0;

            foreach ($groupedTickets as $email => $userTickets) {
                $attachments = [];
                $firstTicket = $userTickets->first();

                foreach ($userTickets as $ticket) {
                    $event = $ticket->ticketCategory?->event;
                    $ticketPrice = $ticket->ticketCategory?->price;
                    $ticketCategoryName = $ticket->ticketCategory?->name;

                    $ticketData = (object) [
                        'ticket_id' => $ticket->id,
                        'ticket_category_id' => $ticket->ticketCategory?->id,
                        'ticket_number' => $ticket->id,
                        'quantity' => $ticket->quantity,
                        'status' => $ticket->status,
                        'event' => $event ? (object) [
                            'id' => $event->id,
                            'title' => $event->title,
                            'location' => $event->location,
                            'start_date' => $event->start_date,
                            'end_date' => $event->end_date,
                            'category' => $event->category ? (object) ['name' => $event->category->name] : null,
                        ] : null,
                        'ticket_category_name' => $ticketCategoryName,
                        'price_per_ticket' => number_format($ticketPrice ?? 0, 2),
                        'total_price' => number_format(($ticketPrice ?? 0) * $ticket->quantity, 2),
                        'user' => $ticket->user ? (object) [
                            'id' => $ticket->user->id,
                            'name' => $ticket->user->name,
                            'email' => $ticket->user->email,
                        ] : null,
                    ];

                    // Generate QR Code
                    $qrPayload = json_encode([
                        'ticket_id' => $ticketData->ticket_id,
                        'user_name' => $ticketData->user->name ?? '',
                        'event_id' => $ticketData->event->id ?? '',
                    ], JSON_UNESCAPED_SLASHES);

                    $qrImage = $this->generateFromPayload($qrPayload);
                    if (!$qrImage)
                        continue; // Skip if QR fails

                    // Generate PDF for this ticket
                    $pdf = Pdf::loadView('tickets.BookingTicketTemplate', [
                        'ticket' => $ticketData,
                        'qrImage' => $qrImage,
                    ])->setPaper('a4', 'landscape');

                    // Add to attachments
                    $attachments[] = [
                        'data' => $pdf->output(),
                        'name' => "ticket_{$ticketData->ticket_number}.pdf",
                        'mime' => 'application/pdf',
                    ];
                }

                // Only send if we have attachments
                if (!empty($attachments)) {
                    Mail::to($email)->send(
                        new SendMail(
                            'Your Booking Tickets',
                            '<p>Please find your booking tickets attached.</p>',
                            true,
                            $attachments
                        )
                    );
                    $sentCount++;
                }
            }

            return response()->json([
                'status' => true,
                'message' => "Booking email(s) sent successfully to {$sentCount} user(s).",
                'sent_to' => $sentCount,
            ], 200);

        } catch (\Exception $e) {
            Log::error('Failed to send booking emails: ' . $e->getMessage(), [
                'ticket_ids' => $ticketIds ?? null,
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to send emails.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    private function generateFromPayload($qrPayload)
    {
        try {
            $qrSvg = QrCode::format('svg')
                ->size(200)
                ->generate($qrPayload);

            return base64_encode($qrSvg);
        } catch (\Exception $e) {
            Log::error('QR Code generation failed: ' . $e->getMessage());
            return null;
        }
    }
}