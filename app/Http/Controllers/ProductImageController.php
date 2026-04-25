<?php

namespace App\Http\Controllers;

use App\Models\ProductImage;
use Illuminate\Http\Response;

class ProductImageController extends Controller
{
    public function show(int $id): Response
    {
        $productImage = ProductImage::query()->findOrFail($id);
        $imageData = $productImage->image_data;

        abort_if(! is_string($imageData) || $imageData === '', 404);
        $mimeType = $this->resolveMimeType($productImage->mime_type, $imageData);

        return response($imageData, 200, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . ($productImage->filename ?: 'image') . '"',
            'Cache-Control' => 'public, max-age=31536000',
        ]);
    }

    protected function resolveMimeType(?string $storedMimeType, string $binary): string
    {
        if (is_string($storedMimeType) && str_starts_with($storedMimeType, 'image/')) {
            return $storedMimeType;
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $detectedMimeType = $finfo->buffer($binary) ?: null;

        if (is_string($detectedMimeType) && str_starts_with($detectedMimeType, 'image/')) {
            return $detectedMimeType;
        }

        return is_string($storedMimeType) && $storedMimeType !== ''
            ? $storedMimeType
            : 'application/octet-stream';
    }
}
