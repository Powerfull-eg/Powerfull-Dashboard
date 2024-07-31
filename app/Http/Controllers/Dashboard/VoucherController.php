<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use App\Models\Voucher;
use App\Models\VoucherOrder;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.vouchers.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = new User();
        return view('dashboard.vouchers.create',compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required',
            'type' => 'required',
            'value' => 'required',
            'user_id' => 'required',
            'min_amount' => 'required|numeric',
            'max_discount' => 'required|numeric',
            'from' => 'required|date',
            'to' => 'required|date',
        ]);
        $voucher = Voucher::create($validated);
        return redirect()->route('dashboard.vouchers.index')->with('success',__('Voucher Created Successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $voucher = Voucher::find($id);
        return view('dashboard.vouchers.show',compact('voucher'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $voucher = Voucher::find($id);
        $users = new User();
        return view('dashboard.vouchers.edit',compact('voucher','users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'code' => 'required',
            'type' => 'required',
            'value' => 'required',
            'user_id' => 'required',
            'min_amount' => 'required|numeric',
            'max_discount' => 'required|numeric',
            'from' => 'required|date',
            'to' => 'required|date',
        ]);
        $voucher = Voucher::find($id);
        $voucher->update($validated);
        return redirect()->route('dashboard.vouchers.index')->with('success',__('Voucher Updated Successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
