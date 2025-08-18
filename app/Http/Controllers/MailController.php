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
            Mail::to('test@example.com')->send(
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
            'ticket_id' => 'required|exists:tickets,id',
        ]);

        $id = $request->ticket_id;

        try {
            $ticket = Ticket::with([
                'ticketCategory:id,event_id,name,price',
                'ticketCategory.event:id,title,location,start_date,end_date,category_id',
                'ticketCategory.event.category:id,name',
                'user:id,name,email',
            ])->findOrFail($id);

            $event = $ticket->ticketCategory?->event;
            $ticketPrice = $ticket->ticketCategory?->price;
            $ticketCategoryId = $ticket->ticketCategory?->id;
            $ticketCategoryName = $ticket->ticketCategory?->name;

            $ticketData = (object) [
                'ticket_id' => $ticket->id,
                'ticket_category_id' => $ticketCategoryId,
                'ticket_number' => 'TKT-' . str_pad($ticket->id, 6, '0', STR_PAD_LEFT),
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

            $qrPayload = json_encode([
                'ticket_id' => $ticketData->ticket_id,
                'user_name' => $ticketData->user->name ?? '',
                'event_id' => $ticketData->event->id ?? '',
            ], JSON_UNESCAPED_SLASHES);

            $qrImage = $this->generateFromPayload($qrPayload);

            if (!$qrImage) {
                return response()->json([
                    'status' => false,
                    'message' => 'QR code generation failed.',
                ], 500);
            }

            $pdf = Pdf::loadView('tickets.BookingTicketTemplate', [
                'ticket' => $ticketData,
                'qrImage' => $qrImage,
            ])->setPaper('a4', 'landscape');

            $attachments = [
                [
                    'data' => $pdf->output(),
                    'name' => "ticket_{$ticketData->ticket_number}.pdf",
                    'mime' => 'application/pdf',
                ],
            ];

            Mail::to($ticketData->user->email)->send(
                new SendMail(
                    'Your Booking Ticket',
                    '<p>Please find your booking ticket attached.</p>',
                    true,
                    $attachments
                )
            );

            return response()->json([
                'status' => true,
                'message' => 'Booking email sent successfully.',
            ], 200);

        } catch (\Exception $e) {
            Log::error('Failed to send booking email: ' . $e->getMessage(), [
                'ticket_id' => $id ?? null,
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Failed to send email.',
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