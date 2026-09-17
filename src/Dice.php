<?php

declare(strict_types=1);

namespace Dungeon;

/** Un dé à N faces. Niveau 1, complété au chapitre Encapsulation. */
class Dice
{
    /**
     * Le nombre de faces, fixé à la création et jamais modifié : readonly.
     * Chapitre Encapsulation : le constructeur doit refuser moins de 2 faces en
     * levant InvalidArgumentException("Un dé a au moins 2 faces, $sides reçu.").
     */
    public function __construct(
        public readonly int $sides,
    ) {
        if($sides<2){
            throw new \InvalidArgumentException("Un dé a au moins 2 faces, $sides recu.");
        }
    }

    /** Fabrique statique : doit renvoyer un dé à 6 faces. */
    public static function d6(): self
    {
        return new self(6);
    }

    /** Fabrique statique : doit renvoyer un dé à 20 faces. */
    public static function d20(): self
    {
        return new self(20);
    }

    /** Doit renvoyer un entier tiré au hasard entre 1 et $sides inclus. */
    public function roll(): int
    {
        return rand(1,$this->sides);
    }
}
