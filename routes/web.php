<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TaskController;
use App\Mail\InvoiceEmail;
use App\Models\Client;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


// Frontend
Route::get('/', function () {
    return view('welcome');
});


// Backend

Route::prefix('/')->middleware(['auth'])->group(function () {

    Route::get('dashboard', function () {

        $user = User::find(Auth::user()->id);
        $todo_task = Task::where('user_id', Auth::user()->id)->where('status', 'pending')->paginate(5);
        // dd($user);
        return view('dashboard')->with([
            'user' => $user,
            'pending_tasks' => $user->tasks->where('status', 'pending'),
            'due_invoices' => $user->invoices->where('status', 'unpaid'),
            'paid_invoices' => $user->invoices->where('status', 'paid'),
            'todo_lists' => $todo_task
        ]);
    })->name('dashboard');


    /**
     * Client Route
     */
    Route::resource('client', ClientController::class);

    /**
     * Task by client
     */
    // Route::get('client/{client:username}', [ClientController::class, 'searchTaskByClient'])->name('searchTaskByClient');

    /**
     * Task Route
     */
    Route::resource('task', TaskController::class);
    Route::put('task/{task}/complete', [TaskController::class, 'markAsComplete'])->name('markAsComplete');

    /**
     * Invoices Route
     */
    Route::prefix('invoices')->group(function () {
        Route::get('/', [InvoiceController::class, 'index'])->name('invoice.index');
        Route::get('create', [InvoiceController::class, 'create'])->name('invoice.create');
        Route::put('{invoice}/update', [InvoiceController::class, 'update'])->name('invoice.update');
        Route::delete('{invoice}/delete', [InvoiceController::class, 'destroy'])->name('invoice.destroy');
        Route::get('search', [InvoiceController::class, 'search'])->name('invoice.search');
        Route::get('invoice', [InvoiceController::class, 'invoice'])->name('invoice');
        Route::get('email/send/{invoice:invoice_id}', [InvoiceController::class, 'sendEmail'])->name('invoice.sendEmail');
    });


    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('settings/update', [SettingsController::class, 'update'])->name('settings.update');

    // Route::get('gazi', function () {
    //     $data = [
    //         'user'          => '',
    //         'invoice_id'    => '',
    //         'invoice'       => '',
    //         'pdf'           => '',
    //     ];
    //     return new InvoiceEmail($data);
    // });
});


require __DIR__ . '/auth.php';
