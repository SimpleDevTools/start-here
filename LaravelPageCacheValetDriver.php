<?php

// Place this file inside of ~/.config/valet/Drivers

use Valet\Drivers\LaravelValetDriver;

class LaravelPageCacheValetDriver extends LaravelValetDriver
{
    /**
     * Determine if the driver serves the request.
     *
     * @param  string  $sitePath
     * @param  string  $siteName
     * @param  string  $uri
     * @return bool
     */
    public function serves($sitePath, $siteName, $uri)
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET'
            && is_dir("$sitePath/public/page-cache")
            && parent::serves($sitePath, $siteName, $uri);
    }

    /**
     * Get the fully resolved path to the application's front controller.
     *
     * @param  string  $sitePath
     * @param  string  $siteName
     * @param  string  $uri
     * @return string
     */
    public function frontControllerPath($sitePath, $siteName, $uri)
    {
        if ($uri === '/') {
            $uri = 'pc__index__pc';
        }

        if (file_exists($cachePath = $sitePath.'/public/page-cache/'.$uri.'.html')) {
            return $cachePath;
        }

        return parent::frontControllerPath($sitePath, $siteName, $uri);
    }
}
