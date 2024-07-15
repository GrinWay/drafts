<?php

namespace App\Attribute;

//TODO (NewClosureDefinitionWithTagPass)
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
class NewClosureDefinitionWithTag
{
    /**
    * NewClosureDefinitionWithTagPass reads @tag parameter
    *
    * For NewClosureDefinitionWithTag as an \Attribute we set index as value for index_by
    * if you use !tagged_locator or !tagged_iterator
    * BUT
    * For Compiler Pass we set index as key of index_by
    */
    public function __construct(
        public readonly string $tag,
        public readonly ?string $index = null,
        public readonly ?string $method = null,
    ) {
    }

    public function __sleep(): array
    {
        return [
            'tag' => $this->tag,
            'index' => $this->index,
            'method' => $this->method,
        ];
    }
}
