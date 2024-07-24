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
 */
namespace PharIo\Manifest;

use Iterator;
use function count;

/** @template-implements Iterator<int,BundledComponent> */
class BundledComponentCollectionIterator implements Iterator {
=======
 * (c) Arne Blankerts <arne@blankerts.de>, Sebastian Heuer <sebastian@phpeople.de>, Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PharIo\Manifest;

class BundledComponentCollectionIterator implements \Iterator {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    /** @var BundledComponent[] */
    private $bundledComponents;

    /** @var int */
    private $position = 0;

    public function __construct(BundledComponentCollection $bundledComponents) {
        $this->bundledComponents = $bundledComponents->getBundledComponents();
    }

    public function rewind(): void {
        $this->position = 0;
    }

    public function valid(): bool {
<<<<<<< HEAD
        return $this->position < count($this->bundledComponents);
=======
        return $this->position < \count($this->bundledComponents);
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    }

    public function key(): int {
        return $this->position;
    }

    public function current(): BundledComponent {
        return $this->bundledComponents[$this->position];
    }

    public function next(): void {
        $this->position++;
    }
}
