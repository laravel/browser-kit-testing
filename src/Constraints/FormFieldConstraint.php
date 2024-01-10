<?php

namespace Laravel\BrowserKitTesting\Constraints;

use PHPUnit\Runner\Version;

if (str_starts_with(Version::series(), '10')) {
    abstract class FormFieldConstraint extends PageConstraint
    {
        use Concerns\FormFieldConstraint;
    }
} else {
    readonly abstract class FormFieldConstraint extends PageConstraint
    {
        use Concerns\FormFieldConstraint;
    }
}
