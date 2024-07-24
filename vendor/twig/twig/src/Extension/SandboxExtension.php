<?php

/*
 * This file is part of Twig.
 *
 * (c) Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Twig\Extension;

use Twig\NodeVisitor\SandboxNodeVisitor;
use Twig\Sandbox\SecurityNotAllowedMethodError;
use Twig\Sandbox\SecurityNotAllowedPropertyError;
use Twig\Sandbox\SecurityPolicyInterface;
<<<<<<< HEAD
use Twig\Sandbox\SourcePolicyInterface;
=======
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
use Twig\Source;
use Twig\TokenParser\SandboxTokenParser;

final class SandboxExtension extends AbstractExtension
{
    private $sandboxedGlobally;
    private $sandboxed;
    private $policy;
<<<<<<< HEAD
    private $sourcePolicy;

    public function __construct(SecurityPolicyInterface $policy, $sandboxed = false, SourcePolicyInterface $sourcePolicy = null)
    {
        $this->policy = $policy;
        $this->sandboxedGlobally = $sandboxed;
        $this->sourcePolicy = $sourcePolicy;
=======

    public function __construct(SecurityPolicyInterface $policy, $sandboxed = false)
    {
        $this->policy = $policy;
        $this->sandboxedGlobally = $sandboxed;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    }

    public function getTokenParsers()
    {
        return [new SandboxTokenParser()];
    }

    public function getNodeVisitors()
    {
        return [new SandboxNodeVisitor()];
    }

    public function enableSandbox()
    {
        $this->sandboxed = true;
    }

    public function disableSandbox()
    {
        $this->sandboxed = false;
    }

<<<<<<< HEAD
    public function isSandboxed(Source $source = null)
    {
        return $this->sandboxedGlobally || $this->sandboxed || $this->isSourceSandboxed($source);
=======
    public function isSandboxed()
    {
        return $this->sandboxedGlobally || $this->sandboxed;
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    }

    public function isSandboxedGlobally()
    {
        return $this->sandboxedGlobally;
    }

<<<<<<< HEAD
    private function isSourceSandboxed(?Source $source): bool
    {
        if (null === $source || null === $this->sourcePolicy) {
            return false;
        }

        return $this->sourcePolicy->enableSandbox($source);
    }

=======
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    public function setSecurityPolicy(SecurityPolicyInterface $policy)
    {
        $this->policy = $policy;
    }

    public function getSecurityPolicy()
    {
        return $this->policy;
    }

<<<<<<< HEAD
    public function checkSecurity($tags, $filters, $functions, Source $source = null)
    {
        if ($this->isSandboxed($source)) {
=======
    public function checkSecurity($tags, $filters, $functions)
    {
        if ($this->isSandboxed()) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
            $this->policy->checkSecurity($tags, $filters, $functions);
        }
    }

    public function checkMethodAllowed($obj, $method, int $lineno = -1, Source $source = null)
    {
<<<<<<< HEAD
        if ($this->isSandboxed($source)) {
=======
        if ($this->isSandboxed()) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
            try {
                $this->policy->checkMethodAllowed($obj, $method);
            } catch (SecurityNotAllowedMethodError $e) {
                $e->setSourceContext($source);
                $e->setTemplateLine($lineno);

                throw $e;
            }
        }
    }

    public function checkPropertyAllowed($obj, $property, int $lineno = -1, Source $source = null)
    {
<<<<<<< HEAD
        if ($this->isSandboxed($source)) {
=======
        if ($this->isSandboxed()) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
            try {
                $this->policy->checkPropertyAllowed($obj, $property);
            } catch (SecurityNotAllowedPropertyError $e) {
                $e->setSourceContext($source);
                $e->setTemplateLine($lineno);

                throw $e;
            }
        }
    }

    public function ensureToStringAllowed($obj, int $lineno = -1, Source $source = null)
    {
<<<<<<< HEAD
        if ($this->isSandboxed($source) && \is_object($obj) && method_exists($obj, '__toString')) {
=======
        if ($this->isSandboxed() && \is_object($obj) && method_exists($obj, '__toString')) {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
            try {
                $this->policy->checkMethodAllowed($obj, '__toString');
            } catch (SecurityNotAllowedMethodError $e) {
                $e->setSourceContext($source);
                $e->setTemplateLine($lineno);

                throw $e;
            }
        }

        return $obj;
    }
}

class_alias('Twig\Extension\SandboxExtension', 'Twig_Extension_Sandbox');
