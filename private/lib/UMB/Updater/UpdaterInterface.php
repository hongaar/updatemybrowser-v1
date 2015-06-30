<?php

interface Umb_Updater_UpdaterInterface
{
    /**
     * @return void
     */
    public function update();

    /**
     * @param string $shortName
     * @param string $channel
     * @param string $platform
     * @return int|bool
     */
    public function getVersion($shortName, $channel = null, $platform = null);
}