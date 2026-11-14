<?php

namespace App\Filament\Resources\ProductTransactions\Tables;

use App\Models\ProductStock;
use App\Models\ProductTransaction;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Support\Enums\Width;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;

class ProductTransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID')->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('product.code')
                    ->label('Kode Produk')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('product.name')
                    ->label('Produk')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('product.product_type')
                    ->label('Jenis Produk')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'semi-finished' => 'Setengah Jadi',
                        'finished' => 'Barang Jadi',
                        default => $state,
                    })
                    ->searchable()
                    ->sortable(),
                TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->formatStateUsing(fn ($state, $record): string => "{$state} {$record->product?->unit}")
                    ->sortable(),
                TextColumn::make('transaction_type')
                    ->label('Tipe Transaksi')
                    ->badge()
                    ->icon(fn (string $state): ?string => match ($state) {
                        'in' => 'heroicon-m-arrow-right-end-on-rectangle',
                        'out' => 'heroicon-m-arrow-left-start-on-rectangle',
                        default => null,
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'in' => 'Masuk',
                        'out' => 'Keluar',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'in' => 'success',
                        'out' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('transaction_date')
                    ->label('Tanggal Transaksi')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->icon(fn (string $state): string => match ($state) {
                        'pending' => 'heroicon-m-clock',
                        'approved' => 'heroicon-m-check',
                        'rejected' => 'heroicon-m-x-mark',
                        default => $state,
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Menunggu Konfirmasi',
                        'approved' => 'Disetujui',
                        'rejected' => 'Ditolak',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('creator.name')
                    ->label('Dibuat Oleh')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime('d M y H:i')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordUrl(null)
            ->recordAction(ViewAction::class)
            ->recordActions([
                Action::make('view_notes')
                    ->label('Catatan')
                    ->icon('heroicon-o-chat-bubble-left-ellipsis')
                    ->color('warning')
                    ->visible(fn (ProductTransaction $record): bool => filled($record->notes))
                    ->modalIcon('heroicon-o-chat-bubble-left-ellipsis')
                    ->modalIconColor('warning')
                    ->modalHeading('Catatan Transaksi')
                    ->modalDescription(fn (ProductTransaction $record): string => "{$record->product?->name} • {$record->quantity} {$record->product?->unit}")
                    ->modalWidth(Width::Medium)
                    ->modalContent(fn (ProductTransaction $record): HtmlString => new HtmlString(
                        '<div class="rounded-xl border border-stone-200/80 bg-stone-50/80 p-4 text-sm leading-relaxed text-stone-800 dark:border-white/10 dark:bg-white/5 dark:text-stone-200">'
                        .nl2br(e((string) $record->notes)).
                        '</div>'
                    ))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup'),
                ViewAction::make(),
                Action::make('approve')
                    ->label('Setujui')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (ProductTransaction $record): bool => $record->status === 'pending')
                    ->disabled(function (ProductTransaction $record): bool {
                        if ($record->transaction_type !== 'out') {
                            return false;
                        }

                        $currentStock = $record->product?->goodStock?->quantity ?? 0;

                        return $currentStock < $record->quantity;
                    })
                    ->tooltip(function (ProductTransaction $record): ?string {
                        if ($record->transaction_type !== 'out') {
                            return null;
                        }

                        $currentStock = $record->product?->goodStock?->quantity ?? 0;

                        if ($currentStock < $record->quantity) {
                            return "Stok tidak mencukupi (Tersedia: {$currentStock} {$record->product?->unit})";
                        }

                        return null;
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Setujui Transaksi Produk')
                    ->modalDescription('Apakah Anda yakin ingin menyetujui transaksi ini? Stok produk akan diperbarui secara otomatis.')
                    ->modalSubmitActionLabel('Ya, Setujui')
                    ->action(function (ProductTransaction $record): void {
                        DB::transaction(function () use ($record): void {
                            $stock = ProductStock::firstOrCreate(
                                [
                                    'product_id' => $record->product_id,
                                    'stock_type' => 'good',
                                ],
                                ['quantity' => 0]
                            );

                            if ($record->transaction_type === 'out') {
                                if ($stock->quantity < $record->quantity) {
                                    Notification::make()
                                        ->title('Stok tidak mencukupi')
                                        ->body("Stok saat ini ({$stock->quantity} {$record->product?->unit}) kurang dari jumlah keluar ({$record->quantity} {$record->product?->unit}).")
                                        ->danger()
                                        ->send();

                                    return;
                                }

                                $stock->decrement('quantity', $record->quantity);
                            } else {
                                $stock->increment('quantity', $record->quantity);
                            }

                            $record->update([
                                'status' => 'approved',
                            ]);

                            Notification::make()
                                ->title('Transaksi berhasil disetujui')
                                ->success()
                                ->send();
                        });
                    }),
                Action::make('reject')
                    ->label('Tolak')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (ProductTransaction $record): bool => $record->status === 'pending')
                    ->modalHeading('Tolak Transaksi Produk')
                    ->modalDescription('Silakan masukkan alasan penolakan transaksi ini.')
                    ->modalSubmitActionLabel('Tolak Transaksi')
                    ->fillForm(fn (ProductTransaction $record): array => [
                        'notes' => $record->notes,
                    ])
                    ->schema([
                        Textarea::make('notes')
                            ->label('Catatan / Alasan Penolakan')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function (ProductTransaction $record, array $data): void {
                        $record->update([
                            'status' => 'rejected',
                            'notes' => $data['notes'],
                        ]);

                        Notification::make()
                            ->title('Transaksi telah ditolak')
                            ->danger()
                            ->send();
                    }),
                EditAction::make()
                    ->visible(fn (ProductTransaction $record): bool => $record->status === 'pending'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
