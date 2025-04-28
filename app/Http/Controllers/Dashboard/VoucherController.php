<?php

namespace App\Http\Controllers\Dashboard;

use App\Exports\VouchersExport;
use App\Models\Campaign;
use App\Models\User;
use App\Models\Voucher;
use App\Models\VoucherOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

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
        $request->validate([
            'navigator' => 'required',
        ]);
        if($request->navigator == 0){
             $this->createVoucher($request);
        } else {
             $this->createCampaign($request);
        }
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
            'multuple_usage' => 'nullable',
            'usage_count' => 'nullable|numeric',
            'from' => 'required|date',
            'to' => 'required|date',
        ]);
        $voucher = Voucher::find($id);
        $validated['usage_count'] = $validated['usage_count'] ?? 0;
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

    // create voucher
    public function createVoucher(Request $request){
        $validated = $request->validate([
            'code' => 'required',
            'type' => 'required',
            'value' => 'required',
            'user_id' => 'required',
            'min_amount' => 'required|numeric',
            'max_discount' => 'required|numeric',
            'multiple_usage' => 'nullable',
            'usage_count' => 'nullable|numeric',
            'from' => 'required|date',
            'to' => 'required|date',
        ]);
        $validated['usage_count'] = $validated['usage_count'] ?? 0;
        $voucher = Voucher::create($validated);
        return redirect()->route('dashboard.vouchers.index')->with('success',__('Voucher Created Successfully'));
    }

    // create campaign
    public function createCampaign(Request $request){
        $validated = $request->validate([
            'campaign_name' => 'required|string',
            'campaign_description' => 'required',
            'vouchers_count' => 'required|numeric',
            'type' => 'required',
            'value' => 'required',
            'min_amount' => 'required|numeric',
            'max_discount' => 'required|numeric',
            'multiple_usage' => 'nullable',
            'usage_count' => 'nullable|numeric',
            'from' => 'required|date',
            'to' => 'required|date',
        ]);
        
        // create campaign
        $campaign = Campaign::create([
            'name' => $validated['campaign_name'],
            'description' => $validated['campaign_description'],
            'start_date' => $validated['from'],
            'end_date' => $validated['to'],
            'admin_id' => auth()->user()->id,
        ]); 
        
        // create vouchers
        for ($i=0; $i < $validated['vouchers_count']; $i++) {
            $code = $this->generateAndCheckCode();

            $vouchers[] = Voucher::create([
                'code' => $code,
                'type' => $validated['type'],
                'value' => $validated['value'],
                'user_id' => 0,
                'min_amount' => $validated['min_amount'],
                'max_discount' => $validated['max_discount'],
                'multiple_usage' => $validated['multiple_usage'] ?? 0,
                'usage_count' => $validated['usage_count'] ?? 0,
                'from' => $validated['from'],
                'to' => $validated['to'],
                'campaign_id' => $campaign->id,
            ]);
        }
        return redirect()->route('dashboard.vouchers.index')->with('success',__('Campaign Created Successfully'));
    }

    private function generateAndCheckCode(){
        $code = Str::random(6);
        $voucher = Voucher::where('code', $code)->first();
        if ($voucher) {
            $this->generateAndCheckCode();
        }
        return $code;
    }

    public function ExportCampaignExcel($campaign) {
        if (!auth()->user()) {
            abort(403);
        }
        $campaign = Campaign::with('vouchers')->find($campaign);
        $campaign->summary = [];
        return Excel::download(new VouchersExport($campaign), "$campaign->name - $campaign->start_date.xlsx");
    }

    // Show Campaign Vouchers
    public function showCampaign($id){
        $campaign = Campaign::find($id);
        return view('dashboard.vouchers.campaign.show',['campaign' => $campaign]);
    }

    // Edit Campaign 
    public function editCampaign($id) {
        $campaign = Campaign::with('vouchers')->find($id);
        return view('dashboard.vouchers.campaign.edit',['campaign' => $campaign]);
    }

    // Update Campaign 
    public function updateCampaign(string $id, Request $request) {
        $campaign = Campaign::with('vouchers')->find($id);

        $validated = $request->validate([
            'campaign_name' => 'required|string',
            'campaign_description' => 'required',
            'vouchers_count' => 'required|numeric',
            'type' => 'required',
            'value' => 'required',
            'min_amount' => 'required|numeric',
            'max_discount' => 'required|numeric',
            'multiple_usage' => 'nullable',
            'usage_count' => 'nullable|numeric',
            'from' => 'required|date',
            'to' => 'required|date',
        ]);
        
        // update campaign
        $campaign->update([
            'name' => $validated['campaign_name'],
            'description' => $validated['campaign_description'],
            'start_date' => $validated['from'],
            'end_date' => $validated['to'],
            'admin_id' => auth()->user()->id,
        ]);

        //update exist vouchers
        foreach($campaign->vouchers as $voucher) {
            $voucher->update([
                'code' => $voucher->code,
                'type' => $validated['type'],
                'value' => $validated['value'],
                'user_id' => 0,
                'min_amount' => $validated['min_amount'],
                'max_discount' => $validated['max_discount'],
                'multiple_usage' => $validated['multiple_usage'] ?? 0,
                'usage_count' => $validated['usage_count'] ?? 0,
                'from' => $validated['from'],
                'to' => $validated['to'],
                'campaign_id' => $campaign->id,
            ]);
        }

        // Add extra vouchers
        if($validated['vouchers_count'] > $campaign->vouchers->count()) {
            // create vouchers
            for ($i=0; $i < ($validated['vouchers_count'] - $campaign->vouchers->count()); $i++) {
                $code = $this->generateAndCheckCode();

                $vouchers[] = Voucher::create([
                    'code' => $code,
                    'type' => $validated['type'],
                    'value' => $validated['value'],
                    'user_id' => 0,
                    'min_amount' => $validated['min_amount'],
                    'max_discount' => $validated['max_discount'],
                    'multiple_usage' => $validated['multiple_usage'] ?? 0,
                    'usage_count' => $validated['usage_count'] ?? 0,
                    'from' => $validated['from'],
                    'to' => $validated['to'],
                    'campaign_id' => $campaign->id,
                ]);
            }
        }

        return redirect()->route('dashboard.vouchers.index')->with('success', __("Campaign Updated Successfully"));
    }

    // Delete Campaign
    public function deleteCampaign ($id) {
        $campaign = Campaign::find($id);
        $campaign->delete();
        return redirect()->route('dashboard.vouchers.index')->with('success', __("Campaign Deleted Successfully"));
    }
}
