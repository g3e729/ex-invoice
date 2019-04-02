@extends('layouts.app')

@section('content')
<div class="container">
    <form action="{{ route('invoices.store') }}" method="POST">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">Customer Info</div>
                    @foreach($products as $product)
                        <input type="hidden" id="{{ 'product-' . $product->id }}" value="{{ $product->price }}">
                    @endforeach
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Customer Name:</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="form-control" id="name">
                            @if ($errors->has('name'))
                                <p class="text-danger">{{ $errors->first('name') }}</p>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="address">Address:</label>
                            <input type="text" name="address" value="{{ old('address') }}" class="form-control" id="address">
                            @if ($errors->has('address'))
                                <p class="text-danger">{{ $errors->first('address') }}</p>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="invoice_date">Invoice Date:</label>
                            <input type="text" name="invoice_date" value="{{ old('invoice_date') }}" class="form-control datepicker" id="invoice_date">
                            @if ($errors->has('invoice_date'))
                                <p class="text-danger">{{ $errors->first('invoice_date') }}</p>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="invoice_number">Invoice #:</label>
                            <input type="text" name="invoice_number" value="{{ old('invoice_number') }}" class="form-control" id="invoice_number">
                            @if ($errors->has('invoice_number'))
                                <p class="text-danger">{{ $errors->first('invoice_number') }}</p>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="due_date">Due Date:</label>
                            <input type="text" name="due_date" value="{{ old('due_date') }}" class="form-control datepicker" id="due_date">
                            @if ($errors->has('due_date'))
                                <p class="text-danger">{{ $errors->first('due_date') }}</p>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="notes">Notes:</label>
                            <textarea name="notes" class="form-control" id="notes">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Items</div>
                    <div class="card-body" id="cart">
                        <button type="button" class="btn btn-default" id="add-product">+ Add Product</button>
                        <div id="product-holder" class="product mb-10 pt-3 pb-3 d-none" style=" border-bottom: 1px solid #000">
                            <button type="button" class="btn btn-danger btn-sm remove mb-2" onclick="removeParent(this);">Remove</button>
                            <select data-name="product_id" class="form-control product-select field" onchange="getPrice(this);">
                                <option value disabled selected>Select Product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->price }})</option>
                                @endforeach
                            </select>
                            <input type="hidden" data-name="price" value="0" class="form-control field" data-price>
                            <input type="number" data-name="quantity" onkeyup="compute();" class="form-control field" placeholder="Quantity" data-quantity>
                            <input type="text" data-name="tax" onkeyup="compute();" class="form-control field" placeholder="Tax" data-tax>
                        </div>
                        @if (count(old('products', [])))
                            @for($count = 0; $count < count(old('products')); $count++)
                                <div class="product mb-10 pt-3 pb-3" style="border-bottom: 1px solid #000">
                                    @if ($count > 1)
                                    <button type="button" class="btn btn-danger btn-sm remove mb-2" onclick="removeParent(this);">Remove</button>
                                    @endif
                                    <select name="{{ "products[{$count}][product_id]" }}" class="form-control product-select field" onchange="getPrice(this);">
                                        @if (! (old('products')[$count]["product_id"] ?? 0))
                                            <option value disabled selected>Select Product</option>
                                        @endif
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" {{ (old('products')[$count]["product_id"] ?? "") == $product->id ? "selected" : "" }}>{{ $product->name }} ({{ $product->price }})</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="{{ "products[{$count}][price]" }}" value="{{ old('products')[$count]["price"] ?? "" }}" class="form-control field" data-price>
                                    <input type="text" name="{{ "products[{$count}][quantity]" }}" value="{{ old('products')[$count]["quantity"] ?? "" }}" onkeyup="compute();" class="form-control field" placeholder="Quantity" data-quantity>
                                    <input type="text" name="{{ "products[{$count}][tax]" }}" value="{{ old('products')[$count]["tax"] ?? "" }}" onkeyup="compute();" class="form-control field" placeholder="Tax" data-tax>
                                </div>
                                @if ($errors->has('products.' . $count . '.id'))
                                    <p class="text-danger">{{ $errors->first('products.' . $count . '.id') }}</p>
                                @elseif ($errors->has('products.' . $count . '.quantity'))
                                    <p class="text-danger">{{ $errors->first('products.' . $count . '.quantity') }}</p>
                                @elseif ($errors->has('products.' . $count . '.tax'))
                                    <p class="text-danger">{{ $errors->first('products.' . $count . '.tax') }}</p>
                                @endif
                            @endfor
                        @endif
                    </div>
                    <div class="card-body">
                        <hr>
                        <input type="hidden" name="amount" value="0">
                        <p id="total">Total: <span></span></p>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">Payment</div>
                    <div class="card-body" id="payment">
                        <button type="button" class="btn btn-default" id="add-payment">+ Add Payment</button>
                        <div id="payment-holder" class="payment mb-10 pt-3 pb-3 d-none" style="border-bottom: 1px solid #000">
                            <button type="button" class="btn btn-danger btn-sm remove mb-2" onclick="removeParent(this);">Remove</button>
                            <select data-name="type" class="form-control field">
                                <option value disabled selected>Select Payment Type</option>
                                @foreach(["Cash", "Check", "Credit"] as $name)
                                    <option value="{{ strtolower($name) }}">{{ $name }}</option>
                                @endforeach
                            </select>
                            <input type="text" class="form-control field" data-name="amount" placeholder="Amount">
                        </div>
                        @if (count(old('payments', [])))
                            @for($count = 0; $count < count(old('payments')); $count++)
                                <div class="payment mb-10 pt-3 pb-3" style="border-bottom: 1px solid #000">
                                    @if ($count > 1)
                                    <button type="button" class="btn btn-danger btn-sm remove mb-2" onclick="removeParent(this);">Remove</button>
                                    @endif
                                    <select name="{{ "payments[{$count}][type]" }}" class="form-control field">
                                        @if (! (old('payments')[$count]["type"] ?? 0))
                                            <option value disabled selected>Select Payment Type</option>
                                        @endif
                                        @foreach(["Cash", "Check", "Credit"] as $id => $name)
                                            <option value="{{ strtolower($name) }}" {{ (old('payments')[$count]["type"] ?? "") == $product->id ? "selected" : "" }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                    <input type="text" class="form-control field" name="{{ "payments[{$count}][amount]" }}" value="{{ old('payments')[$count]["amount"] ?? "" }}" placeholder="Amount">
                                </div>
                                @if ($errors->has('payments.' . $count . '.type'))
                                    <p class="text-danger">{{ $errors->first('payments.' . $count . '.type') }}</p>
                                @elseif ($errors->has('payments.' . $count . '.amount'))
                                    <p class="text-danger">{{ $errors->first('payments.' . $count . '.amount') }}</p>
                                @endif
                            @endfor
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row" style="margin-top: 10px">
            <div class="col-md-6">
                <div class="card">
                    <a href="{{ route('invoices.index') }}" class="btn btn-default">Back</a>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
