<?php

namespace App\Http\Controllers;

use App\Traits\CanUploadFile;
use Illuminate\Http\Request;

class TinymceController extends Controller
{
    use CanUploadFile;

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'file' => 'required|image',
        ]);

        $url = $this->uploadFile(request()->file('file'), 'tinymce');

        return response()->json([
            'location' => $url,
        ]);
    }
}
