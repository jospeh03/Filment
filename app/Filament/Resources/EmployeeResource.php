<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\State;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Employee;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\EmployeeResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\EmployeeResource\RelationManagers;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationGroup='Employee Mangment';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Location Information')
                    ->description('Select the country, state, and city of the employee.')
                    ->schema([
                        Forms\Components\Select::make('country_id')
                            ->label('Country')
                            ->relationship('country', 'name')
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required()
                            //->function (Set $set){
                            // $set ('state_id',null),$set(city_id,'null')}
                            ->afterStateUpdated(fn (Set $set) => [
                                $set('state_id', null),
                                $set('city_id', null), // Also reset city if you're using cascading selects
                            ]),
    
                        Forms\Components\Select::make('state_id')
                            ->label('State')
                            ->options(fn (Get $get): Collection => 
                                \App\Models\State::query()
                                    ->where('country_id', $get('country_id'))
                                    ->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $set('city_id',null)),
    
                        Forms\Components\Select::make('city_id')
                            ->label('City')
                            ->options(fn (Get $get): Collection => 
                                \App\Models\City::query()
                                    ->where('state_id', $get('state_id'))
                                    ->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->live()
                            ->required(),
    
                        Forms\Components\Select::make('department_id')
                            ->label('Department')
                            ->relationship('department', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                    ])->columns(2),
    
                Forms\Components\Section::make('Employee Name')
                    ->description('Enter the employee’s full name.')
                    ->schema([
                        Forms\Components\TextInput::make('first_name')
                            ->label('First Name')
                            ->required()
                            ->maxLength(255),
    
                        Forms\Components\TextInput::make('middle_name')
                            ->label('Middle Name')
                            ->maxLength(255)
                            ->nullable(),
    
                        Forms\Components\TextInput::make('last_name')
                            ->label('Last Name')
                            ->required()
                            ->maxLength(255),
                    ])->columns(3),
    
                Forms\Components\Section::make('Address Details')
                    ->description('Enter the employee’s home address.')
                    ->schema([
                        Forms\Components\TextInput::make('address')
                            ->label('Address')
                            ->required()
                            ->maxLength(255),
    
                        Forms\Components\TextInput::make('zip_code')
                            ->label('ZIP Code')
                            ->required()
                            ->maxLength(255),
                    ])->columns(2),
    
                Forms\Components\Section::make('Employment Dates')
                    ->schema([
                        Forms\Components\DatePicker::make('date_of_birth')
                            ->label('Date of Birth')
                            ->native(false)
                            ->displayFormat('d-Mon-Y')
                            ->required(),
    
                        Forms\Components\DatePicker::make('date_hire')
                            ->label('Date Hired')
                            ->native(false)
                            ->displayFormat('d-Mon-Y')
                            ->required(),
                    ])->columns(2),
            ]);
    }
    

    public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('country.name')
                ->label('Country')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('state.name')
                ->label('State')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('city.name')
                ->label('City')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('department.name')
                ->label('Department')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('first_name')->label('First Name')->searchable(),
            Tables\Columns\TextColumn::make('middle_name')->label('Middle Name')->searchable(),
            Tables\Columns\TextColumn::make('last_name')->label('Last Name')->searchable(),
            Tables\Columns\TextColumn::make('address')->label('Address')->searchable(),
            Tables\Columns\TextColumn::make('zip_code')->label('ZIP Code')->searchable(),

            Tables\Columns\TextColumn::make('date_of_birth')->label('Date of Birth')->date()->sortable(),
            Tables\Columns\TextColumn::make('date_hire')->label('Date Hired')->date()->sortable(),

            Tables\Columns\TextColumn::make('created_at')->label('Created At')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('updated_at')->label('Updated At')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
        ])
        ->filters([
            //
        ])
        ->actions([
            Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'view' => Pages\ViewEmployee::route('/{record}'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
