<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Product;
use App\Http\Requests\InvoiceRequest;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = Invoice::get();

        return view('invoices.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = Product::get();

        return view('invoices.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InvoiceRequest $request)
    {
        $fields = $request->except('products', 'payments');
        $products = $request->get('products');
        $payments = $request->get('payments');

        $invoice = Invoice::create($fields);

        foreach ($products as $product) {
            $tax = $product['tax'] / 100;
            $product['amount'] = $product['price'] + ($product['price'] * $tax);
            $invoice->items()->create($product);
        }

        foreach ($payments as $payment) {
            $invoice->payment_lines()->create($payment);
        }

        return redirect()->route('invoices.show', $invoice);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice $invoice)
    {
        return view('invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit(Invoice $invoice)
    {
        $products = Product::get();

        // dd($invoice->items);

        return view('invoices.edit', compact('invoice', 'products'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(InvoiceRequest $request, Invoice $invoice)
    {
        $fields = $request->except('products', 'payments');
        $products = $request->get('products');
        $payments = $request->get('payments');

        $invoice->update($fields);

        $invoice->items()->delete();
        $invoice->payment_lines()->delete();

        foreach ($products as $product) {
            $tax = $product['tax'] / 100;
            $product['amount'] = $product['price'] + ($product['price'] * $tax);
            $invoice->items()->create($product);
        }

        foreach ($payments as $payment) {
            $invoice->payment_lines()->create($payment);
        }

        return redirect()->route('invoices.show', $invoice);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();

        return redirect()->route('invoices.index');
    }
}
