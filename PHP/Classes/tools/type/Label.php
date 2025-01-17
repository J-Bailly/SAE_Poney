<?php

namespace tools\type;

use tools\GenericFormElement;

final class Label extends GenericFormElement
{
    public function render(): string
    {
        return sprintf(
            '<label for="%s">%s</label>',
            $this->getId(),
            htmlspecialchars($this->getValue(), ENT_QUOTES | ENT_HTML5)
        );
    }
}


?>