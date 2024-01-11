<?php

namespace Laravel\BrowserKitTesting\Constraints;

use PHPUnit\Runner\Version;

if (str_starts_with(Version::series(), '10')) {
    class HasValue extends FormFieldConstraint
    {
        use Concerns\HasValue;
    }
} else {
    readonly class HasValue extends FormFieldConstraint
    {
        use Concerns\HasValue;
    }
}
