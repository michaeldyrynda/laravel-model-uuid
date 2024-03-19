<?php

namespace Dyrynda\Database\Support\Exceptions;

use Exception;

class UnknownGrammarClass extends Exception
{
    /** @var string */
    protected $message = 'Unknown grammar class, unable to define database type.';
}
