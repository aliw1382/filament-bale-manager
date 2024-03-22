<?php

namespace Aliw1382\FilamentBaleManager\Resources\BaleResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class MessageRelationManager extends RelationManager
{
    protected static string $relationship = 'Message';

    /**
     * @return string|null
     */
    public static function getPluralLabel() : ?string
    {
        return __( 'filament-bale-manager::properties.messages' );
    }

    protected static function getModelLabel() : ?string
    {
        return __( 'filament-bale-manager::properties.message' );
    }

    protected static function getPluralModelLabel() : ?string
    {
        return __( 'filament-bale-manager::properties.message' );
    }

    public static function getTitle( Model $ownerRecord, string $pageClass ) : string
    {
        return __( 'filament-bale-manager::properties.messages' );
    }

    /** @var array */
    private static array $info = [];

    /**
     * @param string|null $chat_id
     * @param string $token
     * @return array
     * @throws \Filament\Support\Exceptions\Halt
     */
    private static function getInfo( ?string $chat_id, string $token ) : array
    {

        if ( empty( $chat_id ) ) return [];

        $action = CreateAction::make();

        if ( ! isset( self::$info[ $chat_id ] ) )
        {

            $api_url                = str( filament()->getPlugin( 'bale-manager' )->getApiUrl() )
                ->replace( [ '<token>', '<method>' ], [ $token, 'getChat' ] )
                ->toString()
            ;
            self::$info[ $chat_id ] = Http::post( $api_url, [
                'chat_id' => $chat_id,
            ] )->json();

        }

        $response = self::$info[ $chat_id ];

        if ( ! $response[ 'ok' ] )
        {

            Notification::make()
                        ->danger()
                        ->color( 'warning' )
                        ->title( 'خطایی رخ داد!' )
                        ->body( $response[ 'description' ] )
                        ->seconds( 5 )
                        ->send()
            ;

            $action->halt();

        }

        return $response;
    }

    public function form( Form $form ) : Form
    {
        return $form
            ->schema( [

                Forms\Components\TextInput::make( 'to_chat_id' )
                                          ->required()
                                          ->autocomplete( false )
                                          ->maxLength( 20 )
                                          ->columnSpanFull()
                                          ->label( __( 'filament-bale-manager::properties.labels.to_chat_id' ) ),

                Forms\Components\TextInput::make( 'name' )
                                          ->hiddenOn( [ 'create' ] )
                                          ->formatStateUsing( function ( ?Model $record, RelationManager $livewire ) {

                                              $response = static::getInfo( $record?->to_chat_id, $livewire->ownerRecord->token );
                                              return ( $response[ 'result' ][ 'first_name' ] ?? '' ) . ' ' . ( $response[ 'result' ][ 'last_name' ] ?? '' );

                                          } )
                                          ->label( __( 'filament-bale-manager::properties.labels.name' ) ),

                Forms\Components\TextInput::make( 'username' )
                                          ->hiddenOn( [ 'create' ] )
                                          ->formatStateUsing( function ( ?Model $record, RelationManager $livewire ) {

                                              $response = static::getInfo( $record?->to_chat_id, $livewire->ownerRecord->token );
                                              return ( $response[ 'result' ][ 'username' ] ?? '' );

                                          } )
                                          ->label( __( 'filament-bale-manager::properties.labels.username' ) ),

                Forms\Components\Textarea::make( 'message' )
                                         ->required()
                                         ->columnSpanFull()
                                         ->label( __( 'filament-bale-manager::properties.labels.message' ) )
                                         ->maxLength( 4096 ),


            ] )
        ;
    }

    public function table( Table $table ) : Table
    {
        return $table
            ->recordTitleAttribute( 'Message' )
            ->columns( [

                Tables\Columns\TextColumn::make( 'to_chat_id' )
                                         ->label( __( 'filament-bale-manager::properties.labels.to_chat_id' ) )
                                         ->toggleable()
                                         ->searchable(),

                Tables\Columns\TextColumn::make( 'message' )
                                         ->limit()
                                         ->toggleable()
                                         ->label( __( 'filament-bale-manager::properties.labels.message' ) ),

                Tables\Columns\TextColumn::make( 'created_at' )
                                         ->label( __( 'filament-bale-manager::properties.labels.created_at' ) )->jalaliDateTime( 'H:i:s Y-m-d' ),

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
            ->paginatedWhileReordering()
            ->persistSearchInSession()
            ->persistColumnSearchesInSession()
            ->searchPlaceholder( 'جستجو ( شناسه مقصد )' )
            ->headerActions( [

                CreateAction::make()->mutateFormDataUsing( function ( array $data, CreateAction $action, RelationManager $livewire ) {

                    $token    = $livewire->ownerRecord->token;
                    $api_url  = str( filament()->getPlugin( 'bale-manager' )->getApiUrl() )
                        ->replace( [ '<token>', '<method>' ], [ $token, 'sendMessage' ] )
                        ->toString()
                    ;
                    $response = Http::post( $api_url, [

                        'chat_id' => $data[ 'to_chat_id' ],
                        'text'    => str( $data[ 'message' ] )->limit( 4093 )->toString()

                    ] )->json();

                    if ( ! $response[ 'ok' ] )
                    {

                        Notification::make()
                                    ->danger()
                                    ->color( 'warning' )
                                    ->title( 'خطایی رخ داد!' )
                                    ->body( $response[ 'description' ] )
                                    ->seconds( 5 )
                                    ->send()
                        ;

                        $action->halt();

                    }

                    return $data;

                } ),

            ] )
            ->actions( [

                Tables\Actions\ViewAction::make()->iconButton(),
                Tables\Actions\DeleteAction::make()->iconButton(),

            ] )
            ->bulkActions( [
                Tables\Actions\BulkActionGroup::make( [
                    Tables\Actions\DeleteBulkAction::make(),
                ] ),
            ] )
        ;
    }
}
