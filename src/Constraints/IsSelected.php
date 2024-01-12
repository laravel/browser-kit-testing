<?php

namespace Laravel\BrowserKitTesting\Constraints;

use PHPUnit\Runner\Version;

if (str_starts_with(Version::series(), '10')) {
    class IsSelected extends FormFieldConstraint
    {
        use Concerns\IsSelected;
    }
} else {
    readonly class IsSelected extends FormFieldConstraint
    {
        use Concerns\IsSelected;
    }
}
