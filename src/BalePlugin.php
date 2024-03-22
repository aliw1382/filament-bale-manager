<?php

namespace Aliw1382\FilamentBaleManager;

use Aliw1382\FilamentBaleManager\Resources\BaleResource;
use Filament\Contracts\Plugin;
use Filament\Panel;


class BalePlugin implements Plugin
{

    /** @var string */
    private string $api_url = 'https://tapi.bale.ai/bot<token>/<method>';

    /**
     * @return static
     */
    public static function make() : static
    {
        return app( static::class );
    }

    /**
     * @return string
     */
    public function getId() : string
    {
        return 'bale-manager';
    }

    /**
     * @param Panel $panel
     * @return void
     */
    public function register( Panel $panel ) : void
    {
        $panel->resources( [
            BaleResource::class,
        ] );
    }

    /**
     * @param Panel $panel
     * @return void
     */
    public function boot( Panel $panel ) : void
    {
        // TODO: Implement boot() method.
    }

    /**
     * @param string $api_url
     * @return $this
     */
    public function setApiUrl( string $api_url ) : BalePlugin
    {
        $this->api_url = $api_url;
        return $this;
    }

    /**
     * @return string
     */
    public function getApiUrl() : string
    {
        return $this->api_url;
    }

}
