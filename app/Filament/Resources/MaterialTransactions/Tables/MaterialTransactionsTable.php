<?php

namespace App\Filament\Resources\MaterialTransactions\Tables;

use App\Models\MaterialStock;
use App\Models\MaterialTransaction;
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

class MaterialTransactionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('ID'),
                TextColumn::make('material.name')
                    ->label('Material')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('material.material_type')
                    ->label('Jenis Material')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('quantity')
                    ->label('Jumlah')
                    ->formatStateUsing(fn ($state, $record): string => "{$state} {$record->material?->unit}")
                    ->sortable(),
                TextColumn::make('transaction_type')
                    ->label('Tipe Transaksi')
                    ->badge()
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

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pending' => 'Menunngu Konfirmasi',
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
                    ->visible(fn (MaterialTransaction $record): bool => filled($record->notes))
                    ->modalIcon('heroicon-o-chat-bubble-left-ellipsis')
                    ->modalIconColor('warning')
                    ->modalHeading('Catatan Transaksi')
                    ->modalDescription(fn (MaterialTransaction $record): string => "{$record->material?->name} • {$record->quantity} {$record->material?->unit}")
                    ->modalWidth(Width::Medium)
                    ->modalContent(fn (MaterialTransaction $record): HtmlString => new HtmlString(
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
                    ->visible(fn (MaterialTransaction $record): bool => $record->status === 'pending')
                    ->disabled(function (MaterialTransaction $record): bool {
                        if ($record->transaction_type !== 'out') {
                            return false;
                        }

                        $currentStock = $record->material?->stock?->quantity ?? 0;

                        return $currentStock < $record->quantity;
                    })
                    ->tooltip(function (MaterialTransaction $record): ?string {
                        if ($record->transaction_type !== 'out') {
                            return null;
                        }

                        $currentStock = $record->material?->stock?->quantity ?? 0;

                        if ($currentStock < $record->quantity) {
                            return "Stok tidak mencukupi (Tersedia: {$currentStock} {$record->material?->unit})";
                        }

                        return null;
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Setujui Transaksi Material')
                    ->modalDescription('Apakah Anda yakin ingin menyetujui transaksi ini? Stok material akan diperbarui secara otomatis.')
                    ->modalSubmitActionLabel('Ya, Setujui')
                    ->action(function (MaterialTransaction $record): void {
                        DB::transaction(function () use ($record): void {
                            $stock = MaterialStock::firstOrCreate(
                                ['material_id' => $record->material_id],
                                ['quantity' => 0]
                            );

                            if ($record->transaction_type === 'out') {
                                if ($stock->quantity < $record->quantity) {
                                    Notification::make()
                                        ->title('Stok tidak mencukupi')
                                        ->body("Stok saat ini ({$stock->quantity} {$record->material?->unit}) kurang dari jumlah keluar ({$record->quantity} {$record->material?->unit}).")
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
                    ->visible(fn (MaterialTransaction $record): bool => $record->status === 'pending')
                    ->modalHeading('Tolak Transaksi Material')
                    ->modalDescription('Silakan masukkan alasan penolakan transaksi ini.')
                    ->modalSubmitActionLabel('Tolak Transaksi')
                    ->fillForm(fn (MaterialTransaction $record): array => [
                        'notes' => $record->notes,
                    ])
                    ->schema([
                        Textarea::make('notes')
                            ->label('Catatan / Alasan Penolakan')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function (MaterialTransaction $record, array $data): void {
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
                    ->visible(fn (MaterialTransaction $record): bool => $record->status === 'pending'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
