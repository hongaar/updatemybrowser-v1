<?php

class Umb_Updater_Freshbrowsers extends Umb_Updater_AbstractUpdater
{
    /**
     * {@inheritdoc}
     */
    public function update()
    {
        $url = 'http://fresh-browsers.com/export/browsers.serialized';
        $this->browsers = unserialize(Ajde_Http_Curl::get($url));
    }

    /**
     * @param string $shortName
     * @param string $channel
     * @param string $platform
     * @return int Version
     */
    public function getVersion($shortName, $channel = null, $platform = null)
    {
        return false;
    }


}