<?php

namespace Laravel\BrowserKitTesting\Constraints;

use PHPUnit\Runner\Version;

if (str_starts_with(Version::series(), '10')) {
    class HasElement extends PageConstraint
    {
        use Concerns\HasElement;
    }
} else {
    readonly class HasElement extends PageConstraint
    {
        use Concerns\HasElement;
    }
}
