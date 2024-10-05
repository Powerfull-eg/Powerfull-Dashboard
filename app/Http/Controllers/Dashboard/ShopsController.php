<?php

namespace App\Http\Controllers\Dashboard;

use App\Livewire\ShopOperationsTable;
use App\Models\Shop;
use App\Http\Requests\StoreShopRequest;
use App\Http\Requests\UpdateShopRequest;
use App\Models\ShopsData;
use App\Models\ShopsMenu;
use App\Models\Station;
use Illuminate\Http\Request;

use function Pest\Laravel\json;

class ShopsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $startDate = $request->startDate ?? null;
        $endDate = $request->endDate ?? null;
        return view("dashboard.shops.index", compact('startDate','endDate'));
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
     * Display the specified resource.
     */
    public function show(Request $request,string $id)
    {
        // Date Filter
        $startDate = $request->startDate ?? null;
        $endDate = $request->endDate ?? null;

        $shop = Shop::with(['device','operations','gifts'])->find($id);
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
            'menu_images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $admin = auth('admins')->user()->id;
        $validated["updated_by"] = $admin;
        $shop = Shop::findOrfail($id);
        $updated = $shop->update($validated);
        
        $data = ShopsData::where('shop_id',$id)->first();
        if($data){
            // Add Or update Shop extra data
            ShopsData::where('shop_id',$id)->update([
                'admin_id' => $admin,
                'shop_id' => $id,
                'type_id' => $validated['type_id'],
                'logo' => $shop->logo,
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
                'logo' => $shop->logo,
                'opens_at' => $validated['opens_at'],
                'closes_at' => $validated['closes_at'],
                'lat' => $validated['location_latitude'],
                'lng' => $validated['location_longitude'],
                'price' => $validated['price'],
            ]);
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

    // Create Shop type
    public function createType(Request $request)
    {
        $validated = $request->validate([
            "name" => "required|string|max:255",
        ]);
        $admin = auth('admins')->user()->id;
        $validated["admin_id"] = $admin;
        $validated["slug"] = Str::slug($validated["name"]);
        $type = ShopType::create($validated);
        return redirect()->back()->with('success', __("Shop Type Created Successfully"));
    }

    // Update Shop type
    public function updateType(Request $request,string $id){
        $validated = $request->validate([
            "name" => "required|string|max:255",
        ]);
        $admin = auth('admins')->user()->id;
        $validated["admin_id"] = $admin;
        $validated["slug"] = Str::slug($validated["name"]);
        $type = ShopType::findOrfail($id);
        $updated = $type->update($validated);
        return redirect()->back()->with('success', __("Shop Type Updated Successfully"));
    }
    
}
