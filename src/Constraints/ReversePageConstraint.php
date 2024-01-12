<?php

namespace Laravel\BrowserKitTesting\Constraints;

use PHPUnit\Runner\Version;

if (str_starts_with(Version::series(), '10')) {
    class ReversePageConstraint extends PageConstraint
    {
        use Concerns\ReversePageConstraint;
    }
} else {
    readonly class ReversePageConstraint extends PageConstraint
    {
        use Concerns\ReversePageConstraint;
    }
}
