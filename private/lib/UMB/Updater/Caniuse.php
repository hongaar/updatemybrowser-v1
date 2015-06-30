<?php

class Umb_Updater_Caniuse extends Umb_Updater_AbstractUpdater
{
    /**
     * {@inheritdoc}
     */
    public function update()
    {
        $url = 'https://raw.githubusercontent.com/Fyrd/caniuse/master/fulldata-json/data-2.0.json';
        $this->browsers = @json_decode(Ajde_Http_Curl::get($url));
    }

    /**
     * @param string $shortName
     * @param string $channel
     * @param string $platform
     * @return int|bool Version
     */
    public function getVersion($shortName, $channel = null, $platform = null)
    {
        $agents = $this->browsers->agents;

        if (isset($agents->$shortName)) {
            return $agents->$shortName->current_version;
        }

        return false;
    }
}