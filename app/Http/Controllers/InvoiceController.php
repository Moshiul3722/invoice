<?php

namespace App\Http\Controllers;

use App\Mail\InvoiceEmail;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    // public function index()
    // {
    //     return '<h1>Moshiul</h1>';
    // }
    public function index(Request $request)
    {
        $invoices = Invoice::with('client')->latest();

        if (!empty($request->client_id)) {
            $invoices = $invoices->where('client_id', $request->client_id);
        }
        if (!empty($request->status)) {
            $invoices = $invoices->where('status', $request->status);
        }
        if (!empty($request->email_sent)) {
            $invoices = $invoices->where('email_sent', $request->email_sent);
        }

        $invoices = $invoices->paginate(10);

        return view('invoice.index')->with([
            'clients' => Client::where('user_id', Auth::user()->id)->get(),
            'invoices' => $invoices
        ]);
    }

    public function create(Request $request)
    {
        $tasks = false;

        if (!empty($request->client_id) && !empty($request->status)) {
            //    dd($request->all());
            $request->validate([
                'client_id' => ['required', 'not_in:none'],
                'status' => ['required', 'not_in:none'],
                // 'fromDate' => ['required', 'not_in:none'],
                // 'endDate' => ['required', 'not_in:none'],
            ]);

            $tasks = $this->getInvoiceData($request);
        }

        return view('invoice.create')->with([
            'clients'   => Client::where('user_id', Auth::user()->id)->get(),
            'tasks'      => $tasks
        ]);
    }

    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $invoice
     * @return void
     */
    public function update(Request $request, Invoice $invoice)
    {
        $invoice->update([
            'status' => $invoice->status == 'unpaid' ? 'paid' : 'unpaid'
        ]);

        return redirect()->route('invoice.index')->with('success', 'Paid successfully.');
    }

    /**
     * destroy
     *
     * @param  mixed $invoice
     * @return void
     */
    public function destroy(Invoice $invoice)
    {
        Storage::delete('public/invoices/' . $invoice->download_url);
        $invoice->delete();
        return redirect()->route('invoice.index')->with('success', 'Invoices Payment marked as Paid!');
    }


    /**
     * getInvoiceData
     *
     * @param  mixed $request
     * @return void
     */
    public function getInvoiceData(Request $request)
    {
        $task = Task::latest();

        if (!empty($request->client_id)) {
            $task = $task->where('client_id', '=', $request->client_id);
        }
        if (!empty($request->status)) {
            $task = $task->where('status', '=', $request->status);
        }
        if (!empty($request->fromDate)) {
            $task = $task->whereDate('created_at', '>=', $request->fromDate);
        }
        if (!empty($request->endDate)) {
            $task = $task->whereDate('created_at', '<=', $request->endDate);
        }
        return $task->get();
    }

    /**
     * invoice
     *
     * @param  mixed $request
     * @return void
     */

    public function invoice(Request $request)
    {
        if (!empty($request->generate_pdf) && $request->generate_pdf == 'yes') {
            $this->generatePDF($request);
            return redirect()->route('invoice.index')->with('success', 'Invoice Created');
        }
        if (!empty($request->preview) && $request->preview == 'yes') {

            if (!empty($request->discount) && !empty($request->discount_type)) {
                $discount = $request->discount;
                $discount_type = $request->discount_type;
            } else {
                $discount = 0;
                $discount_type = '';
            }

            // dd($request->all());

            $tasks = Task::whereIn('id', $request->invoice_ids)->get();
            return view('invoice.preview')->with([
                'user'          => Auth::user(),
                'invoice_no'    => 'INVO-' . rand(23456, 23456789),
                'tasks'         => $tasks,
                'discount'      => $discount,
                'discount_type' => $discount_type
            ]);
        }
    }

    /**
     * generatePDF
     *
     * @param  mixed $request
     * @return void
     */
    public function generatePDF(Request $request)
    {

        $invo_on = 'INVO-' . rand(23456, 23456789);

        $tasks = Task::whereIn('id', $request->invoice_ids)->get();

        // dd($tasks->first()->client->id);

        if (!empty($request->discount) && !empty($request->discount_type)) {
            $discount = $request->discount;
            $discount_type = $request->discount_type;
        } else {
            $discount = 0;
            $discount_type = '';
        }

        $data = [
            'user'          => Auth::user(),
            'invoice_no'    => $invo_on,
            'tasks'         => $tasks,
            'discount'      => $discount,
            'discount_type' => $discount_type
        ];

        $pdf = PDF::loadView('invoice.pdf', $data);

        Storage::put('public/invoices/' . $invo_on . '.pdf', $pdf->output());

        Invoice::create([
            'invoice_id'    => $invo_on,
            'client_id'     => $tasks->first()->client->id,
            'user_id'       => Auth::user()->id,
            'status'        => 'unpaid',
            'amount'        => $tasks->sum('price'),
            'download_url'  => $invo_on . '.pdf'
        ]);

        // return $pdf->download($invo_on.'.pdf');
        // return redirect()->route('invoice.index')->with('success', 'Invoice Created');
    }

    public function sendEmail(Invoice $invoice)
    {

        // $pdf = Storage::get('public/invoices/' . $invoice->download_url);
        $pdf        = public_path('storage/invoices/' . $invoice->download_url);

        $data = [
            'user'          => Auth::user(),
            'invoice_id'    => $invoice->invoice_id,
            'invoice'       => $invoice,
            'pdf'           => $pdf,
        ];

        Mail::to($invoice->client)->send(new InvoiceEmail($data));

        // Mail::send('emails.invoice', $data, function ($message) use ($invoice, $pdf) {
        //     $message->from(Auth::user()->email, Auth::user()->name);
        //     $message->to($invoice->client->email, $invoice->client->name);
        //     $message->subject('Pixcafe - ' . $invoice->invoice_id);
        //     $message->attachData($pdf, $invoice->download_url, [
        //         'mime' => 'application/pdf'
        //     ]);
        // });

        $invoice->update([
            'email_sent' => 'yes'
        ]);

        return redirect()->route('invoice.index')->with('success', 'Email Sent');
    }
}
