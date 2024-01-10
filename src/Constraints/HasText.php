<?php

namespace Laravel\BrowserKitTesting\Constraints;

use PHPUnit\Runner\Version;

if (str_starts_with(Version::series(), '10')) {
    class HasText extends PageConstraint
    {
        use Concerns\HasText;
    }
} else {
    readonly class HasText extends PageConstraint
    {
        use Concerns\HasText;
    }
}
