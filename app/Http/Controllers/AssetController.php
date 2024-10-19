<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\assetsService;

class AssetController extends Controller
{
    protected $assetsService;

    public function __construct(assetsService $assetsService)
    {
        $this->assetsService = $assetsService;
    }

    /**
     * Handle file upload request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadFile(Request $request)
    {
        // Validate the request to ensure a file is provided
        $request->validate([
            'file' => 'required|file',
        ]);

        // Create a file DTO (Data Transfer Object) for file handling
        $fileDTO = (object) [
            'file' => $request->file('file'),
            'name' => $request->file('file')->getClientOriginalName(),
            'alt_text' => $request->input('alt_text', null),
        ];

        // Use the assetsService to store the file
        $uploadedFile = $this->assetsService->handleFileUpload($fileDTO);

        // Return the response with the file URL
        return response()->json([
            'message' => 'File uploaded successfully!',
            'file' => $uploadedFile,
        ]);
    }
}
