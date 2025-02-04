<?php

namespace App\Http\Controllers\Dashboard;

use App\Livewire\ShopOperationsTable;
use App\Models\Shop;
use App\Http\Requests\StoreShopRequest;
use App\Http\Requests\UpdateShopRequest;
use App\Jobs\CloneShops;
use App\Models\ShopsData;
use App\Models\ShopsMenu;
use App\Models\Station;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use function Pest\Laravel\json;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ShopExportExcel;
use PDF;

class ShopsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $startDate = $request->startDate ?? null;
        $endDate = $request->endDate ?? null;
        $shops = new Shop();
        $shops = $startDate ? $shops->where('created_at','<=',$startDate) : $shops;
        $shops = $endDate ? $shops->where('created_at','>=',$endDate) : $shops;
        $shops = $shops->with('device','data')->get();
        return view("dashboard.shops.index", compact('startDate','endDate','shops'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("dashboard.shops.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreShopRequest $request)
    {   
        // 
    }

    /**
     * Display the Shop Operations.
     */
    public function operations(string $shop,Request $request)
    {
        // Date Filter
        $startDate = $request->startDate ?? null;
        $endDate = $request->endDate ?? null;
      
        $shop = $this->getShopData($shop,$startDate,$endDate);
        return view("dashboard.shops.show-operation",compact('shop','startDate','endDate'));

    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request,string $id)
    {
        // Date Filter
        $startDate = $request->startDate ?? null;
        $endDate = $request->endDate ?? null;

        $shop = Shop::with(['device','operations','gifts','notes'])->find($id);
        $this->authorize('view', $shop);
        $totalAmount = 0;
        $totalHours = 0;
        $totalGifts = $startDate || $endDate ? $shop->gifts->where('created_at','>=',$startDate)->where('created_at','<=',$endDate)->count() : $shop->gifts->count();
        
        
        $operations = $startDate || $endDate ? $shop->device->operations->where('created_at','>=',$startDate)->where('created_at','<=',$endDate) : $shop->device->operations;
        // $operations[] = $endDate ? $shop->device->operations->where('created_at','<=',$endDate) : null;
        
        foreach($operations as $operation){
            $totalAmount += ($operation->amount ?? 0);
            $totalHours += ($operation->returnTime && $operation->borrowTime ? floatval((strtotime($operation->returnTime) - strtotime($operation->borrowTime) )/ 60 /60) : 0);
        }

        return view("dashboard.shops.show",compact('shop','totalAmount','totalHours','totalGifts','startDate','endDate'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Shop $shop)
    {
        $shop = Shop::find($shop->id);
        return view("dashboard.shops.edit",compact('shop'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,string $id)
    {
        $validated = $request->validate([
            "name" => "required|string|max:255",
            "phone" => "required|string|starts_with:010,011,012,015|size:11",
            'location_latitude' => 'string',
            'location_longitude' => 'string',
            'opens_at' => 'string',
            'closes_at' => 'string',
            'price' => 'string',
            'type_id' => 'numeric|exists:shops_types,id',
            'price_id' => 'required|numeric|exists:prices,id',
            'menu_images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        
        $admin = auth('admins')->user()->id;
        $validated["updated_by"] = $admin;
        // logo
        $shop = Shop::findOrfail($id);
        if($request->logo){
            if(filter_var($request->logo, FILTER_VALIDATE_URL) !== false){
                $validated['logo'] = $request->logo; 
            }else{
                $validated['logo'] = time() .'-' . $request->file('logo')->getClientOriginalName();
                $request->file('logo')->storePubliclyAs("public/shops/" . $shop->id, $validated['logo']);
            } 
        }

        $updated = $shop->update($validated);
        
        $data = ShopsData::where('shop_id',$id)->first();
        if($data){
            // Add Or update Shop extra data
            ShopsData::where('shop_id',$id)->update([
                'admin_id' => $admin,
                'shop_id' => $id,
                'type_id' => $validated['type_id'],
                'opens_at' => $validated['opens_at'],
                'closes_at' => $validated['closes_at'],
                'lat' => $validated['location_latitude'],
                'lng' => $validated['location_longitude'],
                'price' => $validated['price'],
            ]);
        }else{
            ShopsData::create([
                'admin_id' => $admin,
                'shop_id' => $id,
                'type_id' => $validated['type_id'],
                'opens_at' => $validated['opens_at'],
                'closes_at' => $validated['closes_at'],
                'lat' => $validated['location_latitude'],
                'lng' => $validated['location_longitude'],
                'price' => $validated['price'],
            ]);
        }
        // Add logo to shops data table
        if($request->data_logo){
            $data = ShopsData::where('shop_id',$id)->first();
            $logo = time() .'-data-' . $request->file('data_logo')->getClientOriginalName();
            $request->file('data_logo')->storePubliclyAs("public/shops/" . $id, $logo);
            $data->logo = $logo;
            $data->save();
        }
        // Add menu
        $shopMenu = ShopsMenu::where('shop_id',$id);
        $images = $request->file('menu_images');
        // Old Images
        if(isset($request->preloaded) && count($request->preloaded) > 0){
            foreach($shopMenu->get() as $image){
                if(!in_array($image->id, $request->preloaded)){
                    $image->delete();
                }
            }
        }else{ $shopMenu->delete(); }

        // New Upoladed Image
        if(isset($validated['menu_images']) && count($validated['menu_images']) > 0){
            foreach($validated['menu_images'] as $image){
                // Storing image
                $name = time() . '-' . $image->getClientOriginalName() ;
                $path = $image->storePubliclyAs("public/shops/$id/menu/", $name);

                $shopMenu->create([
                    'shop_id' => $id,
                    'image' => $name
                ]);
            }
        }

        return $updated 
        ? redirect(route('dashboard.shops.index'))->with('success', __("Shop Updated Successfully"))
        : redirect()->back()->with('error', __("Failed Update Shop")); 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shop $shop)
    {
        $shop =  Shop::find($shop->id);
        $shop->delete();
        return redirect()->back();
    }

    // Fetch Shop data
    public function syncShopData(){
        try{
           CloneShops::dispatch();
           return redirect()->back()->with('success', __("Shops Fetched Successfully"));
        }catch(\Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    
    // Update Shop Menu only
    function updateShopMenu(Request $request,string $id) {
        $validated = Validator::validate($request->all(), [
            'menu_images.*' => [
                'required',
                File::image()->types(['jpeg','png','jpg','gif','svg'])->max(2048),
            ],
        ]);

      // Add menu
      $shopMenu = ShopsMenu::where('shop_id',$id);
      $images = $request->file('menu_images');
      // Old Images
      if(isset($request->preloaded) && count($request->preloaded) > 0){
          foreach($shopMenu->get() as $image){
              if(!in_array($image->id, $request->preloaded)){
                  $image->delete();
              }
          }
      }else{ $shopMenu->delete(); }

      // New Upoladed Image
      if(isset($validated['menu_images']) && count($validated['menu_images']) > 0){
          foreach($validated['menu_images'] as $image){
              // Storing image
              $name = time() . '-' . $image->getClientOriginalName() ;
              $path = $image->storePubliclyAs("public/shops/$id/menu/", $name);

              $shopMenu->create([
                  'shop_id' => $id,
                  'image' => $name
              ]);
          }

      }
      return redirect()->back()->with('success', __("Menu Updated Successfully"));
    }

    // get Shop Data
    private function getShopData(string $id, $startDate = null, $endDate = null){
        $shop = Shop::with(['device','operations','gifts','notes'])->find($id);
        $operations = $startDate? $shop->operations->where('created_at','>=',$startDate) : $shop->operations;
        $operations = $endDate? $operations->where('created_at','<=',$endDate) : $operations;
        

        $shop->startDate = $startDate ?? null;
        $shop->endDate = $endDate ?? null;

        $summary["NumberOfCustomers"] = $operations->pluck('user_id')->unique()->count() . " customer";
        $summary["NumberOfOperations"] = $operations->count() . " order";
        $summary["NumberOfOperatingHours"] = 0;
        foreach($operations as $operation){
            $summary["NumberOfOperatingHours"] += ($operation->returnTime && $operation->borrowTime ? ceil(floatval((strtotime($operation->returnTime) - strtotime($operation->borrowTime) )/ 60 /60)) : 0);
        }
        $summary["NumberOfOperatingHours"] .= " hours"; 

        $summary["totalOperationsAmount"] = $operations->sum('amount') . " EGP";
        $summary["PartnerSharePercentage"] = $shop->share_percentage. "%";
        $summary["MerchantShareAmount"] = intval($operations->sum('amount') * ($shop->share_percentage / 100)) . " EGP";
        
        $shop->operations = $operations;

        $shop->summary = $summary;

        return $shop;
    }

    // Report Pdf for specific shop
    public function exportShopPdf($id,Request $request){
        $view = "dashboard.pdf.shop";
        $data = $this->getShopData($id,$request->startDate,$request->endDate);
        $pdf = PDF::loadView($view, ['data' => $data]);
        return $pdf->stream("$data->name-report.pdf");
    }

    // Report Pdf for specific shop
    public function exportShopExcel($id,Request $request){
        $data = $this->getShopData($id,$request->startDate,$request->endDate);
        
        $excel = ShopExportExcel::class;
        return Excel::download(new $excel($data,$request->startDate,$request->endDate), "$data->name.xlsx");
    }

}
