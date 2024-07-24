<?php declare(strict_types = 1);
/*
 * This file is part of PharIo\Manifest.
 *
<<<<<<< HEAD
 * Copyright (c) Arne Blankerts <arne@blankerts.de>, Sebastian Heuer <sebastian@phpeople.de>, Sebastian Bergmann <sebastian@phpunit.de> and contributors
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
=======
 * (c) Arne Blankerts <arne@blankerts.de>, Sebastian Heuer <sebastian@phpeople.de>, Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
 */
namespace PharIo\Manifest;

use DOMElement;
use DOMNodeList;
<<<<<<< HEAD
use function sprintf;
=======
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98

class ManifestElement {
    public const XMLNS = 'https://phar.io/xml/manifest/1.0';

    /** @var DOMElement */
    private $element;

    public function __construct(DOMElement $element) {
        $this->element = $element;
    }

    protected function getAttributeValue(string $name): string {
        if (!$this->element->hasAttribute($name)) {
            throw new ManifestElementException(
<<<<<<< HEAD
                sprintf(
=======
                \sprintf(
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
                    'Attribute %s not set on element %s',
                    $name,
                    $this->element->localName
                )
            );
        }

        return $this->element->getAttribute($name);
    }

<<<<<<< HEAD
    protected function hasAttribute(string $name): bool {
        return $this->element->hasAttribute($name);
    }

=======
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    protected function getChildByName(string $elementName): DOMElement {
        $element = $this->element->getElementsByTagNameNS(self::XMLNS, $elementName)->item(0);

        if (!$element instanceof DOMElement) {
            throw new ManifestElementException(
<<<<<<< HEAD
                sprintf('Element %s missing', $elementName)
=======
                \sprintf('Element %s missing', $elementName)
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
            );
        }

        return $element;
    }

    protected function getChildrenByName(string $elementName): DOMNodeList {
        $elementList = $this->element->getElementsByTagNameNS(self::XMLNS, $elementName);

        if ($elementList->length === 0) {
            throw new ManifestElementException(
<<<<<<< HEAD
                sprintf('Element(s) %s missing', $elementName)
=======
                \sprintf('Element(s) %s missing', $elementName)
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
            );
        }

        return $elementList;
    }

    protected function hasChild(string $elementName): bool {
        return $this->element->getElementsByTagNameNS(self::XMLNS, $elementName)->length !== 0;
    }
}
