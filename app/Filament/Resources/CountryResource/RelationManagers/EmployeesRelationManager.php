<?php

namespace App\Filament\Resources\CountryResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeesRelationManager extends RelationManager
{
    protected static string $relationship = 'employees';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('first_name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('first_name')
            ->columns([
                Tables\Columns\TextColumn::make('first_name'),
            Tables\Columns\TextColumn::make('state.name')
                ->label('State')
                ->searchable()
                ->sortable()->toggleable(isToggledHiddenByDefault:true),

            Tables\Columns\TextColumn::make('city.name')
                ->label('City')
                ->searchable()
                ->sortable()->toggleable(isToggledHiddenByDefault:true),

            Tables\Columns\TextColumn::make('department.name')
                ->label('Department')
                ->searchable()
                ->sortable()->toggleable(isToggledHiddenByDefault:true),

            Tables\Columns\TextColumn::make('middle_name')->label('Middle Name')->searchable()->toggleable(isToggledHiddenByDefault:true),
            Tables\Columns\TextColumn::make('last_name')->label('Last Name')->searchable(),
            Tables\Columns\TextColumn::make('address')->label('Address')->searchable()->toggleable(isToggledHiddenByDefault:true),
            Tables\Columns\TextColumn::make('zip_code')->label('ZIP Code')->searchable(),

            Tables\Columns\TextColumn::make('date_of_birth')->label('Date of Birth')->date()->sortable()->toggleable(isToggledHiddenByDefault:true),
            Tables\Columns\TextColumn::make('date_hire')->label('Date Hired')->date()->sortable(),

            Tables\Columns\TextColumn::make('created_at')->label('Created At')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('updated_at')->label('Updated At')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
