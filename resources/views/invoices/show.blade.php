@extends('layouts.app')

@section('content')
<div class="container">
    <form action="{{ route('invoices.store') }}" method="POST">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">Customer Info</div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Customer Name:</label>
                            <input type="text" name="name" value="{{ $invoice->name }}" class="form-control" id="name" disabled>
                        </div>
                        <div class="form-group">
                            <label for="address">Address:</label>
                            <input type="text" name="address" value="{{ $invoice->address }}" class="form-control" id="address" disabled>
                        </div>
                        <div class="form-group">
                            <label for="invoice_date">Invoice Date:</label>
                            <input type="text" name="invoice_date" value="{{ $invoice->invoice_date->format('Y-m-d') }}" class="form-control datepicker" id="invoice_date" disabled>
                        </div>
                        <div class="form-group">
                            <label for="invoice_number">Invoice #:</label>
                            <input type="text" name="invoice_number" value="{{ $invoice->invoice_number }}" class="form-control" id="invoice_number" disabled>
                        </div>
                        <div class="form-group">
                            <label for="due_date">Due Date:</label>
                            <input type="text" name="due_date" value="{{ $invoice->due_date->format('Y-m-d') }}" class="form-control datepicker" id="due_date" disabled>
                        </div>
                        <div class="form-group">
                            <label for="notes">Notes:</label>
                            <textarea name="notes" class="form-control" id="notes" disabled>{{ $invoice->notes }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Items</div>
                    <div class="card-body">
                        <table class="table">
							<thead>
                        		<tr>
                        			<td>Product</td>
                                    <td>Qty</td>
                        			<td>Price</td>
                        			<td>Tax</td>
                        			<td></td>
                        		</tr>
                        	</thead>
							<tbody>
								@foreach($invoice->items as $item)
                        		<tr>
                                    <td>{{ $item->product_name }}</td>
                        			<td>{{ $item->quantity }}</td>
                        			<td>{{ number_format($item->amount, 2) }}</td>
                        			<td>{{ $item->tax }}%</td>
                        			<td>{{ number_format($item->amount, 2) }}</span></td>
                        		</tr>
                        		@endforeach
							</tbody>
							<tfoot>
                        		<tr>
                        			<td colspan="3">Total</td>
                        			<td>{{ number_format($invoice->items->sum('amount'), 2) }}</td>
                        		</tr>
                        	</tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">Payment</div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <td>Type</td>
                                    <td>Amount</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoice->payment_lines as $item)
                                <tr>
                                    <td>{{ ucwords($item->type) }}</td>
                                    <td>{{ number_format($item->amount, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" style="margin-top: 10px">
            <div class="col-md-6">
                <div class="card">
                    <a href="{{ route('invoices.index') }}" class="btn btn-default">Back to list</a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-primary">Edit</a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
@endpush
