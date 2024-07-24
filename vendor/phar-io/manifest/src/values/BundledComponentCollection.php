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

use Countable;
use IteratorAggregate;
use function count;

/** @template-implements IteratorAggregate<int,BundledComponent> */
class BundledComponentCollection implements Countable, IteratorAggregate {
=======
 * (c) Arne Blankerts <arne@blankerts.de>, Sebastian Heuer <sebastian@phpeople.de>, Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PharIo\Manifest;

class BundledComponentCollection implements \Countable, \IteratorAggregate {
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    /** @var BundledComponent[] */
    private $bundledComponents = [];

    public function add(BundledComponent $bundledComponent): void {
        $this->bundledComponents[] = $bundledComponent;
    }

    /**
     * @return BundledComponent[]
     */
    public function getBundledComponents(): array {
        return $this->bundledComponents;
    }

    public function count(): int {
<<<<<<< HEAD
        return count($this->bundledComponents);
=======
        return \count($this->bundledComponents);
>>>>>>> 49369b033194767f4de0877a45b04f3226134f98
    }

    public function getIterator(): BundledComponentCollectionIterator {
        return new BundledComponentCollectionIterator($this);
    }
}
