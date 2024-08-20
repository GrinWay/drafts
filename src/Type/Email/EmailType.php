<?php

namespace App\Type\Email;

/**
* First create new \App\Email\Style\<EmailType>Email
* Then add a new <EmailType>
*
* \App\Email\Style\<Default>Email => EmailType::<DEFAULT>
* \App\Email\Style\<Congratulation>Email => EmailType::<CONGRATULATION>
* ...
*
*/
class EmailType
{
    public const NEW_USER = '%s (new user design)';
    public const ERROR = '%s (error design)';
    public const CONGRATULATION = '%s (congratulation design)';
    /* DEFAULT IN THE END OF THE LIST */
    public const DEFAULT = '%s (default design)';
}
