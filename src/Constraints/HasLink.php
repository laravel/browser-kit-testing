<?php

namespace Laravel\BrowserKitTesting\Constraints;

use PHPUnit\Runner\Version;

if (str_starts_with(Version::series(), '10')) {
    class HasLink extends PageConstraint
    {
        use Concerns\HasLink;
    }
} else {
    readonly class HasLink extends PageConstraint
    {
        use Concerns\HasLink;
    }
}
