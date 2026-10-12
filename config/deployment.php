<?php

return [
    // Trust forwarding headers only from explicitly configured proxy addresses/CIDRs.
    'trusted_proxies' => array_values(array_filter(array_map('trim', explode(',', (string) env('TRUSTED_PROXIES', ''))))),
];
