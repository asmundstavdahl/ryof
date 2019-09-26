<?php

return [
    '/' => [GeneralController::class, 'home'],
    '/info' => [GeneralController::class, 'info'],
    '/square/(?<x>\d+)' => [GeneralController::class, 'square'],
    '/sentence/(?<items>([^,]+)(,[^,]+)*)' => [GeneralController::class, 'sentence'],
];
