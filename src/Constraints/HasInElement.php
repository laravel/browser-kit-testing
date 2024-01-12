<?php

namespace Laravel\BrowserKitTesting\Constraints;

use PHPUnit\Runner\Version;

if (str_starts_with(Version::series(), '10')) {
    class HasInElement extends PageConstraint
    {
        use Concerns\HasInElement;
    }
} else {
    readonly class HasInElement extends PageConstraint
    {
        use Concerns\HasInElement;
    }
}
