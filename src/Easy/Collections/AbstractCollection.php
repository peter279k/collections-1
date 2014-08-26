<?php

// Copyright (c) Lellys Informática. All rights reserved. See License.txt in the project root for license information.
namespace Easy\Collections;

use ArrayIterator;
use Easy\Collections\Comparer\NumericKeyComparer;
use Easy\Collections\Generic\ComparerInterface;
use Easy\Generics\EquatableInterface;
use Traversable;

/**
 * Provides the abstract base class for a strongly typed collection.
 */
abstract class AbstractCollection implements CollectionInterface, CollectionConvertableInterface, EquatableInterface
{
    protected $array = array();

    /**
     * @var ComparerInterface
     */
    private $defaultComparer;

    public function getIterator()
    {
        return new ArrayIterator($this->array);
    }

    /**
     * Gets the default comparer for this collection
     * @return ComparerInterface
     */
    public function getDefaultComparer()
    {
        if ($this->defaultComparer === null) {
            $this->defaultComparer = new NumericKeyComparer();
        }
        return $this->defaultComparer;
    }

    /**
     * Sets the default comparer for this collection
     * @param ComparerInterface $defaultComparer
     * @return ArrayList
     */
    public function setDefaultComparer(ComparerInterface $defaultComparer)
    {
        $this->defaultComparer = $defaultComparer;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->toArray());
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $this->array = array();
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty()
    {
        return $this->count() < 1;
    }

    /**
     * {@inheritdoc}
     */
    public function values()
    {
        return array_values($this->array);
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize($this->array);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized)
    {
        $this->array = unserialize($serialized);
        return $this->array;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return get_class($this);
    }

    public function equals($obj)
    {
        return ($obj === $this);
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        $array = array();
        foreach ($this->array as $key => $value) {
            if ($value instanceof CollectionInterface) {
                $array[$key] = $value->toArray();
            } else {
                $array[$key] = $value;
            }
        }
        return $array;
    }

    /**
     * {@inheritdoc}
     */
    public function toKeysArrays()
    {
        return array_keys($this->array);
    }

    /**
     * {@inheritdoc}
     */
    public function concat(CollectionConvertableInterface $collection)
    {
        $this->array = array_merge_recursive($this->array, $collection->toArray());
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public static function fromItems(Traversable $items)
    {
        return new static($items);
    }
}