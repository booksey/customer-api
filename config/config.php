<?php

declare(strict_types=1);

use Laminas\ConfigAggregator\ConfigAggregator;
use Laminas\ConfigAggregator\PhpFileProvider;

return (new ConfigAggregator([
    new PhpFileProvider('config/autoload/{{,*.}global,{,*.}' . (string)getenv('APP_ENV') . '}.php')
]))->getMergedConfig();
