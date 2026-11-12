<?php

namespace App\Filament\Resources\Employees\Pages;

use App\Filament\Resources\Employees\EmployeeResource;
use App\Models\User;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ListEmployees extends ListRecords
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->using(function (array $data, string $model): Model {
                    return DB::transaction(function () use ($data, $model): Model {
                        $user = User::create([
                            'name' => $data['name'],
                            'email' => $data['email'],
                            'password' => $data['password'],
                        ]);

                        if (! empty($data['role'])) {
                            $user->syncRoles([$data['role']]);
                        }

                        $data['user_id'] = $user->id;

                        return $model::create($data);
                    });
                }),
        ];
    }
}
