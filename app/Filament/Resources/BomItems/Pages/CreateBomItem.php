<?php

namespace App\Filament\Resources\BomItems\Pages;

use App\Filament\Resources\BomItems\BomItemResource;
use App\Models\Product;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CreateBomItem extends CreateRecord
{
    protected static string $resource = BomItemResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(function () use ($data): Model {
            $product = Product::findOrFail($data['product_id']);

            foreach ($data['items'] as $item) {
                $product->bomItems()->create([
                    'material_id' => $item['material_id'],
                    'quantity' => $item['quantity'],
                ]);
            }

            return $product;
        });
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
