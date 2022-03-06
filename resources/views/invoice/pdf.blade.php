<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        html,
        body {
            width: 100%;
            height: 100vh;
            box-sizing: border-box;
            font-family: arial, sans-serif;
            overflow-x: hidden;
        }

        .mainLayout {
            width: 100%;
            max-width: 991px;
            margin: 0 auto;
        }

        .invoice_no,
        .invoice_to,
        .invoice_form {
            display: flex;
            flex-direction: column;
            flex-grow: 1;
            align-self: flex-end;
            margin-bottom: 1rem;
        }

        .invoice_title h1 {
            font-size: 2.5rem;
            font-weight: 700;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #D6EEEE;
        }

        .invoice_info {
            display: flex;
            justify-content: space-between;
        }

        .invoice_total {
            display: flex;
            justify-content: space-between;
        }

        /* .invoice_total_info,
        .invoice_due {
            display: flex;
            justify-content: flex-end;
        } */

        .invoice_total_info table,
        .invoice_due table {
            width: 30%;
        }

        .invoice_total {
            display: flex;
            justify-content: center;
            border-top: 2px solid rgb(0, 0, 255, 0.2);
            border-bottom: 2px solid rgb(0, 0, 255, 0.2);
            align-items: center;
            font-size: 1.5rem;
            font-weight: 700;
        }

        .invoice_total h3,
        p {
            margin: 10px 10px
        }

        .invoice_info h3,
        p {
            margin: 10px 0;
        }

        .invoice_table table tbody tr:last-child {
            border-bottom: 2px solid rgb(0, 0, 255, 0.2);
        }

        .invoice_table table tbody tr td:nth-child(2n) {
            text-align: center;
        }

        .invoice_table table tbody tr td:last-child {
            text-align: right;
        }

        .invoice_table table thead th {
            background: rgb(0, 0, 255, 0.2);
        }

        /* .invoice_total_info {
            margin-top: 20px;
            border-bottom: 2px solid rgb(0, 0, 255, 0.2);
        }

        .invoice_total_info table tbody td:last-child,
        .invoice_due table tbody td:last-child {
            text-align: right;
        } */

        .inoice_copyright {
            font-size: 12px;
            margin-bottom: 50px;
        }

        .invoice_due table {
            background: red;
            margin-top: 10px;
        }

        .invoice_due table tbody tr {
            color: #fff;
            font-size: 18px;
        }

        .invoice_form h2,
        .invoice_to h2 {
            font-size: 1.5rem;
            font-weight: 600
        }

        .invoice_form {
            align-items: flex-end;
        }

        .single_head_title {
            display: flex;
            justify-content: space-between;
        }

    </style>
</head>

<body>
    <div class="mainLayout">

        <div class="invoice_title">
            <div class="title" style="float: left">
                <h1>Invoice</h1>
            </div>
            <div class="inv-logo" style="float: right">
                @if (request('preview') == 'yes')
                    @if (file_exists('storage/uploads/invoice_logo.png'))
                        <img src="{{ asset('storage/uploads/invoice_logo.png') }}" alt="">
                    @else
                        <img src="{{ asset('img/pixcafenetwork.jpg') }}" alt="">
                    @endif
                @else
                    @if (file_exists('storage/uploads/invoice_logo.png'))
                        <img src='storage/uploads/invoice_logo.png' alt="">
                    @else
                        <img src='img/pixcafenetwork.jpg' alt="">
                    @endif

                @endif

            </div>
        </div>
        <div style="clear: both"></div>
        <div class="invoice_info">

            <div class="invoice_no">
                <div class="single_head_title">
                    <span class="w-1/3">INVOICE NO</span>
                    <strong class="w-3/5"><span class="mx-5">:</span> {{ $invoice_no }}</strong>
                </div>
                <div class="single_head_title">
                    <span class="w-1/3">INVOICE DATE</span>
                    <strong class="w-3/5"><span class="mx-5">:</span>
                        {{ Carbon\Carbon::now()->format('d M, Y') }}</strong>
                </div>
                <div class="single_head_title">
                    <span class="w-1/3">INVOICE DUE</span>
                    <strong class="w-3/5"><span class="mx-5">:</span>
                        {{ Carbon\Carbon::now()->addDays(5)->format('d M, Y') }}</strong>
                </div>

            </div>
            <div class="invoice_to">
                <h2>Invoice To</h2>
                <span><strong>{{ $tasks->first()->client->name }}</strong></span>
                <span>{{ $tasks->first()->client->email }}</span>
                <span>{{ $tasks->first()->client->phone }}</span>
                <span>{{ $tasks->first()->client->country }}</span>
            </div>
            <div class="invoice_form">
                <h2>From</h2>
                <span><strong>{{ $user->name }}</strong></span>
                <span>{{ $user->company }}</span>
                <span>{{ $user->phone }}</span>
                <span>{{ $user->country }}</span>
            </div>

        </div>
        <div class="invoice_total">
            <h3>Invoice Total :</h3>
            <p>${{ number_format($tasks->sum('price'), 2) }}</p>
        </div>
        <div class="invoice_table">
            <table>
                <thead>
                    <th>Description</th>
                    {{-- <th style="text-align: center">Descount ($)</th> --}}
                    <th style="text-align: right">Amount ($)</th>
                </thead>
                <tbody>

                    @foreach ($tasks as $task)
                        <tr>
                            <td>{{ $task->name }}</td>
                            {{-- <td>0</td> --}}
                            <td>{{ number_format($task->price, 2) }}</td>
                        </tr>
                    @endforeach


                </tbody>
            </table>
        </div>



        @php
            $main_discount = $discount;
            if ($discount_type == '%') {
                $discount = ($tasks->sum('price') * $discount) / 100;
            } else {
                $discount = $discount;
            }
        @endphp


        <div class="invoice_total_info" style="position: relative">
            <table style="position: absolute; right:0">
                <tr>
                    <td>Subtotal:</td>
                    <td style="text-align: right">${{ number_format($tasks->sum('price'), 2) }}
                    </td>
                </tr>
                <tr>
                    <td>Discount :<span style="font-size:12px">
                            {{ $discount_type == '%' ? '(' . $main_discount . '%)' : '' }}</span></td>
                    <td style="text-align: right">
                        {{ '$' . $discount }}
                    </td>
                </tr>
                <tr>
                    <td>Total:</td>
                    <td style="text-align: right">${{ number_format($tasks->sum('price') - $discount, 2) }}</td>
                </tr>
                <tr>
                    <td style="background-color: red; color:white">Due Amount:</td>
                    <td style="text-align: right; background-color:red; color:white">
                        ${{ number_format($tasks->sum('price') - $discount, 2) }}</td>
                </tr>
            </table>
        </div>
        <div style="clear: both"></div>
        <div class="inoice_copyright">
            &copy; Copyright Pixcafe Network. All Right Reserved.
        </div>

    </div>
</body>

</html>
