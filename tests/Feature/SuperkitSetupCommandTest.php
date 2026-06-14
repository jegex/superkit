<?php

test('setup command registers successfully', function () {
    $this->artisan('list')
        ->expectsOutputToContain('superkit:setup');
});
