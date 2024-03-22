<?php

namespace Aliw1382\FilamentBaleManager\Resources\BaleResource\Pages;

use Aliw1382\FilamentBaleManager\Resources\BaleResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Tables\Actions\CreateAction;
use Illuminate\Support\Facades\Http;

class CreateBale extends CreateRecord
{
    protected static string $resource = BaleResource::class;

    /**
     * @param array $data
     * @return array|mixed[]
     * @throws \Filament\Support\Exceptions\Halt
     */
    protected function mutateFormDataBeforeCreate( array $data ) : array
    {

        $action   = CreateAction::make();
        $api_url  = str( filament()->getPlugin( 'bale-manager' )->getApiUrl() )
            ->replace( [ '<token>', '<method>' ], [ $data[ 'token' ], 'getme' ] )
            ->toString()
        ;
        $response = Http::get( $api_url )->json();

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

        $data[ 'user_id' ]  = auth()->id();
        $data[ 'bot_id' ]   = $response[ 'result' ][ 'id' ];
        $data[ 'name' ]     = $response[ 'result' ][ 'first_name' ];
        $data[ 'username' ] = $response[ 'result' ][ 'username' ];

        return $data;

    }

}
