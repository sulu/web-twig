<?php

declare(strict_types=1);

/*
 * This file is part of Sulu.
 *
 * (c) Sulu GmbH
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Sulu\Twig\Extensions\Node;

use Twig\Compiler;
use Twig\Node\Node;

/**
 * @internal this class is only for internal use and should not be used by someone else
 */
final class PortalNode extends Node
{
    /**
     * @param Node<Node> $body
     */
    public function __construct(string $name, Node $body, int $lineno, string $tag = null)
    {
        parent::__construct(['body' => $body], ['name' => $name], $lineno, $tag);
    }

    public function compile(Compiler $compiler): void
    {
        $compiler->addDebugInfo($this);

        if ($compiler->getEnvironment()->isDebug()) {
            $compiler->write("ob_start();\n");
        } else {
            $compiler->write("ob_start(function () { return ''; });\n");
        }
        $compiler
            ->subcompile($this->getNode('body'))
        ;

        // TODO find a better way then using a static function
        $compiler->raw("\Sulu\Twig\Extensions\PortalExtension::addPortal(");
        $compiler->raw("'" . $this->getAttribute('name') . "', ");
        $compiler->raw("('' === \$tmp = ob_get_clean()) ? '' : new Markup(\$tmp, \$this->env->getCharset())");
        $compiler->raw(");\n");
    }
}
