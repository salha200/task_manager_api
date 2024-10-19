<?php

namespace App\Traits;

use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

trait FileUploadTrait
{
    /**
     * Store the uploaded file securely.
     *
     * @param  object  $fileDTO
     * @return array
     * @throws \Exception|\Symfony\Component\HttpFoundation\File\Exception\FileException
     */
    public function storeFile($fileDTO)
    {
        $file = $fileDTO->file;
        $originalName = $file->getClientOriginalName();

        // Ensure the file extension is valid and there is no path traversal in the file name
        if (preg_match('/\.[^.]+\./', $originalName)) {
            throw new Exception(trans('general.notAllowedAction'), 403);
        }

        // Check for path traversal attack (e.g., using ../ or ..\ or / to go up directories)
        if (strpos($originalName, '..') !== false || strpos($originalName, '/') !== false || strpos($originalName, '\\') !== false) {
            throw new Exception(trans('general.pathTraversalDetected'), 403);
        }

        // Validate the MIME type to ensure it's an allowed file type (including non-images)
        $allowedMimeTypes = [
            'image/jpeg', 'image/png', 'image/gif', 'image/webp', // Images
            'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // Documents
            'application/zip', 'application/x-rar-compressed', // Archives
        ];
        $mime_type = $file->getClientMimeType();

        if (!in_array($mime_type, $allowedMimeTypes)) {
            throw new FileException(trans('general.invalidFileType'), 403);
        }

        // Generate a safe, random file name
        $fileName = Str::random(32);

        $extension = $file->getClientOriginalExtension(); // Safe way to get file extension
        $filePath = "uploads/{$fileName}.{$extension}";

        // Store the file securely
        $path = Storage::disk('local')->putFileAs('uploads', $file, $fileName . '.' . $extension);

        // Get the full URL path of the stored file
        $url = Storage::disk('local')->url($path);

        // Store file metadata in the database or return as response
        return [
            'name' => $fileDTO->name ?? $originalName,
            'path' => $url,
            'mime_type' => $mime_type,
            'alt_text' => $fileDTO->alt_text ?? null,
        ];
    }
}
