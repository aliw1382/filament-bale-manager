<?php

namespace Aliw1382\FilamentBaleManager\Resources;

use Aliw1382\FilamentBaleManager\Models\Bot;
use Aliw1382\FilamentBaleManager\Resources\BaleResource\RelationManagers\MessageRelationManager;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class BaleResource extends Resource
{

    protected static ?string $model = Bot::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-bottom-center-text';

    public static function getPluralLabel() : ?string
    {
        return __( 'filament-bale-manager::properties.bots' );
    }

    public static function getModelLabel() : string
    {
        return __( 'filament-bale-manager::properties.bot' );
    }

    public static function getNavigationLabel() : string
    {
        return __( 'filament-bale-manager::properties.navigation.label' );
    }

    public static function form( Form $form ) : Form
    {
        return $form
            ->schema( [

                TextInput::make( 'name' )
                         ->label( __( 'filament-bale-manager::properties.labels.name' ) )
                         ->hiddenOn( 'create' )
                         ->disabledOn( 'edit' ),

                TextInput::make( 'username' )
                         ->label( __( 'filament-bale-manager::properties.labels.username' ) )
                         ->hiddenOn( 'create' )
                         ->disabledOn( 'edit' ),

                TextInput::make( 'bot_id' )
                         ->label( __( 'filament-bale-manager::properties.labels.bot_id' ) )
                         ->hiddenOn( 'create' )
                         ->disabledOn( 'edit' ),

                TextInput::make( 'token' )
                         ->label( __( 'filament-bale-manager::properties.labels.token' ) )
                         ->autocomplete( false )
                         ->password()
                         ->regex( '/^[0-9]{8,10}:[a-zA-Z0-9_-]{40}$/' )
                         ->revealable()
                         ->unique( ignorable: fn( $record ) => $record )
                         ->extraInputAttributes( [ 'class' => 'text-center' ] )
                         ->disabledOn( 'edit' ),

            ] )
        ;
    }


    public static function table( Table $table ) : Table
    {
        return $table
            ->columns( [

                Tables\Columns\TextColumn::make( 'name' )
                                         ->label( __( 'filament-bale-manager::properties.labels.name' ) )
                                         ->searchable(),

                Tables\Columns\TextColumn::make( 'username' )
                                         ->label( __( 'filament-bale-manager::properties.labels.username' ) )
                                         ->searchable(),

                Tables\Columns\TextColumn::make( 'bot_id' )
                                         ->label( __( 'filament-bale-manager::properties.labels.id' ) . ' ' . __( 'filament-bale-manager::properties.bot' ) )
                                         ->searchable(),

                Tables\Columns\TextColumn::make( 'created_at' )
                                         ->label( __( 'filament-bale-manager::properties.labels.created_at' ) )->jalaliDate(),

            ] )
            ->filters( [

                Tables\Filters\Filter::make( 'created_at' )
                                     ->form( [
                                         DatePicker::make( 'created_from' )->label( 'از تاریخ' ),
                                         DatePicker::make( 'created_until' )->label( 'تا تاریخ' ),
                                     ] )
                                     ->query( function ( Builder $query, array $data ) : Builder {
                                         return $query
                                             ->when(
                                                 $data[ 'created_from' ],
                                                 fn( Builder $query, $date ) : Builder => $query->whereDate( 'created_at', '>=', $date ),
                                             )
                                             ->when(
                                                 $data[ 'created_until' ],
                                                 fn( Builder $query, $date ) : Builder => $query->whereDate( 'created_at', '<=', $date ),
                                             )
                                         ;
                                     } )

            ] )
            ->reorderable( 'sort' )
            ->paginatedWhileReordering()
            ->persistSearchInSession()
            ->persistColumnSearchesInSession()
            ->searchPlaceholder( 'جستجو ( نام ، نام کاربری ، شناسه ربات )' )
            ->actions( [

                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),

            ] )
            ->bulkActions( [

                Tables\Actions\BulkActionGroup::make( [

                    Tables\Actions\DeleteBulkAction::make(),

                ] ),

            ] )
        ;
    }

    public static function getRelations() : array
    {
        return [

            MessageRelationManager::make()

        ];
    }

    protected static ?string $slug = 'bots';

    public static function getPages() : array
    {
        return [
            'index'  => BaleResource\Pages\ListBales::route( '/' ),
            'view'   => BaleResource\Pages\EditBale::route( '/{record}/' ),
            'create' => BaleResource\Pages\CreateBale::route( '/new/create' ),
        ];
    }
}
