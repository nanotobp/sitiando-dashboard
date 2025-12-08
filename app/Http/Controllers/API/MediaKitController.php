<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\MediaKitService;
use App\Models\MediaKitAsset;

class MediaKitController extends Controller
{
    protected $service;

    public function __construct(MediaKitService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => $this->service->list(),
        ]);
    }

    public function download($id)
    {
        $asset = MediaKitAsset::findOrFail($id);

        $this->service->incrementDownload($asset);

        return response()->json([
            'success' => true,
            'url' => $asset->file_url,
        ]);
    }
}
