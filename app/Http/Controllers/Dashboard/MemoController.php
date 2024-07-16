<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Memo;
use Illuminate\Http\Request;

class MemoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.memos.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.memos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'date' => ['nullable', 'date'],
            'content' => ['nullable', 'string'],
        ]);

        Memo::create($request->all());

        return redirect()->route('dashboard.memos.index')->with('success', __(':resource has been created.', ['resource' => __('Memo')]));
    }

    /**
     * Display the specified resource.
     */
    public function show(Memo $memo)
    {
        return view('dashboard.memos.show', [
            'memo' => $memo,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Memo $memo)
    {
        return view('dashboard.memos.edit', [
            'memo' => $memo,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Memo $memo)
    {
        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'date' => ['nullable', 'date'],
            'content' => ['nullable', 'string'],
        ]);

        $memo->update($request->all());

        return redirect()->route('dashboard.memos.index')->with('success', __(':resource has been updated.', ['resource' => __('Memo')]));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Memo $memo)
    {
        $memo->delete();

        return redirect()->route('dashboard.memos.index')->with('success', __(':resource has been deleted.', ['resource' => __('Memo')]));
    }
}
