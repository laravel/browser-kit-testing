<?php

namespace Laravel\BrowserKitTesting\Constraints;

use PHPUnit\Runner\Version;

if (str_starts_with(Version::series(), '10')) {
    class HasSource extends PageConstraint
    {
        use Concerns\HasSource;
    }
} else {
    readonly class HasSource extends PageConstraint
    {
        use Concerns\HasSource;
    }
}
