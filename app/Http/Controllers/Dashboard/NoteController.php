<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Note;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NoteController extends Controller
{
    public function store(Request $request){
        $validated = $request->validate([
            'note' => 'required|string|max:255',
            'type' => 'required',
            'type_id' => 'required',
        ]);
        $validated['admin_id'] = auth('admins')->user()->id;
        $note = Note::create($validated);

        return redirect()->back()->with('success',__('Note Created Successfully'));
    }

    public function show(Note $note){
        $type = DB::table($note->type)->where('id',$note->type_id)->first();
        $notes = Note::where('type_id',$note->type_id)->where('type',$note->type)->get();
        return view('dashboard.notes.show',compact('note','notes','type'));
    }
}
