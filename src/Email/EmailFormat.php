<?php

namespace App\Email;

use App\Contract\Email\EmailInterface;
use App\Contract\Email\EmailFormatInterface;
use App\Type\Email\EmailType;

class EmailFormat implements EmailFormatInterface
{
    private readonly string $emailLowerShortName;
    /**
    * [CONST_NAME => VALUE, ...]
    */
    private readonly array $allEmailTypes;

    public function __construct(
        private readonly EmailInterface $email,
    ) {
        //###>
        $reflEmail = new \ReflectionClass($email);
        $this->emailLowerShortName = \strtolower($reflEmail->getShortName());

        //###>
        $reflEmailTypes = new \ReflectionClass(EmailType::class);
        $this->allEmailTypes = $reflEmailTypes->getConstants();
    }

    public function __invoke(): string
    {
        foreach ($this->allEmailTypes as $constantName => $val) {
            $constantName = \preg_replace('~_~', '', \strtolower($constantName));
            if (\str_starts_with($this->emailLowerShortName, $constantName)) {
                return $val;
            }
        }

        return $val;
    }
}
