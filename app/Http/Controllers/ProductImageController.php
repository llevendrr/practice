<?php

namespace App\Http\Controllers;

use App\Models\ProductImage;
use Illuminate\Http\Response;

class ProductImageController extends Controller
{
    public function show(ProductImage $productImage): Response
    {
        abort_if(blank($productImage->image_data), 404);

        return response($productImage->image_data, 200, [
            'Content-Type' => $productImage->mime_type ?: 'application/octet-stream',
            'Content-Disposition' => 'inline; filename="' . ($productImage->filename ?: 'image') . '"',
            'Cache-Control' => 'public, max-age=31536000',
        ]);
    }
}
