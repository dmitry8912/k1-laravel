<?php

return [
    /*
     * URL of a Gitea service, wich used as exported AD authentication service
     */
    'gitea_url' => env('GITEA_URL', ''),

    /*
     * Interval, separated live locks and dead locks
     */
    'lock_keepalive' => env('LOCK_KEEPALIVE', 15)
];
