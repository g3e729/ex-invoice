@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Invoice List</div>
                <table class="table">
                    <thead>
                        <tr>
                            <td>Customer</td>
                            <td>Date</td>
                            <td>Amount</td>
                            <td>Due Date</td>
                            <td></td>
                        </tr>
                    </thead>
                    <tbody>
                        @if (! $invoices->count())
                            <tr>
                                <td colspan="5" style="text-align: center;">
                                    <p>No data</p>
                                    <a href="{{ route('invoices.create') }}" class="btn btn-primary">Create Invoice</a>
                                </td>
                            </tr>
                        @endif
                        @foreach($invoices as $invoice)
                        <tr>
                            <td><a href="{{ route('invoices.show', $invoice) }}">{{ $invoice->name }}</a></td>
                            <td>{{ $invoice->invoice_date->format('Y-m-d') }}</td>
                            <td>{{ number_format($invoice->amount, 2) }}</td>
                            <td>{{ $invoice->due_date->format('Y-m-d') }}</td>
                            <td><a href="{{ route('invoices.show', $invoice) }}" class="btn btn-primary">View</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
