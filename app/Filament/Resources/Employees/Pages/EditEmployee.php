<?php

namespace App\Filament\Resources\Employees\Pages;

use App\Filament\Resources\Employees\EmployeeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EditEmployee extends EditRecord
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['email'] = $this->getRecord()->user?->email;
        $data['role'] = $this->getRecord()->user?->roles->first()?->name;

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return DB::transaction(function () use ($record, $data): Model {
            if ($record->user) {
                $userData = [
                    'name' => $data['name'],
                    'email' => $data['email'],
                ];

                if (filled($data['password'] ?? null)) {
                    $userData['password'] = $data['password'];
                }

                $record->user->update($userData);

                if (! empty($data['role'])) {
                    $record->user->syncRoles([$data['role']]);
                }
            }

            $record->update($data);

            return $record;
        });
    }
}
