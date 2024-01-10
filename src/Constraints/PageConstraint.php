<?php

namespace Laravel\BrowserKitTesting\Constraints;

use PHPUnit\Framework\Constraint\Constraint;

use PHPUnit\Runner\Version;

if (str_starts_with(Version::series(), '10')) {
    abstract class PageConstraint extends Constraint
    {
        use Concerns\PageConstraint;
    }
} else {
    readonly abstract class PageConstraint extends Constraint
    {
        use Concerns\PageConstraint;
    }
}
