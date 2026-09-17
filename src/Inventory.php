<?php

declare(strict_types=1);

namespace Dungeon;

/**
 * Le sac du héros. Niveau 2.
 * Countable et IteratorAggregate (niveau 4) permettent count($sac) et foreach ($sac as $item).
 *
 * @implements \IteratorAggregate<int, Item>
 */
final class Inventory implements \Countable, \IteratorAggregate
{
    /** @var Item[] Les objets transportés. */
    private array $items = [];

    /** Le poids maximum transportable, fixé à la création : readonly. */
    public function __construct(
        public readonly float $maxWeight = 20.0,
    ) {}

    /**
     * Doit ajouter l'objet et renvoyer true.
     * Niveau 2 : si le poids dépasse maxWeight, ne rien ajouter et renvoyer false.
     * Niveau 3 : à la place du false, lever une InventoryFullException.
     */
    public function add(Item $item): bool
    {
        if ($this->totalWeight() + $item->weight > $this->maxWeight) {
            return false;
        }

        $this->items[] = $item;
        return true;
    }

    /** Doit dire si un objet portant ce nom est dans le sac. */
    public function has(string $name): bool
    {
        foreach ($this->items as $item) {
            if ($name === $item->name) {
                return true;
            }
        }
        return false;
    }

    /** Doit retirer le premier objet portant ce nom (et ne rien faire s'il n'y est pas). */
    public function remove(string $name): void
    {
        foreach ($this->items as $index => $item) {
            if ($item->name === $name) {
                unset($this->items[$index]);
                break;
            }
        }
    }

    /** Doit renvoyer le nombre d'objets dans le sac. */
    public function count(): int
    {
        return count($this->items);
    }

    /** Doit renvoyer la somme des poids. */
    public function totalWeight(): float
    {
        $total = 0.0;

        foreach ($this->items as $item) {
            $total += $item->weight;
        }

        return $total;
    }

    /**
     * Doit permettre le foreach sur l'inventaire. Niveau 4.
     *
     * @return \Traversable<int, Item>
     */
    public function getIterator(): \Traversable
    {
        throw new \LogicException('À implémenter');
    }
}
