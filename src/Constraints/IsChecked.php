<?php

namespace Laravel\BrowserKitTesting\Constraints;

use PHPUnit\Runner\Version;

if (str_starts_with(Version::series(), '10')) {
    class IsChecked extends FormFieldConstraint
    {
        use Concerns\IsChecked;
    }
} else {
    readonly class IsChecked extends FormFieldConstraint
    {
        use Concerns\IsChecked;
    }
}
