<?php

namespace tools\type;

use tools\GenericFormElement;

final class TextArea extends GenericFormElement
{
    public function render(): string
    {
        return sprintf(
            '<textarea name="form[%s]" %s id="%s">%s</textarea>', 
            $this->getName(),
            $this->isRequired() ? 'required="required"' : '',
            $this->getId(),
            htmlspecialchars((string)$this->getValue(), ENT_QUOTES | ENT_HTML5)
        );
    }
}

?>