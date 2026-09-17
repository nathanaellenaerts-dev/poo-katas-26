<?php

declare(strict_types=1);

namespace Dungeon;

use InvalidArgumentException;

/**
 * Un objet ramassable. Classe simple au niveau 2, ABSTRAITE au niveau 3 :
 * on ne ramasse jamais "un objet", on ramasse une arme ou une potion.
 */
abstract class Item implements \Stringable
{
    /**
     * Nom, poids et rareté sont fixés à la création : readonly, lisibles par tout le monde.
     * Doit refuser un poids négatif : InvalidArgumentException("Un poids n'est pas négatif, $weight reçu.").
     */
    public function __construct(
        public readonly string $name,
        public readonly float $weight,
        public readonly Rarity $rarity = Rarity::Common,
    ) {
        if($weight<0)
            {
        throw new InvalidArgumentException("Un poids n'est pas négatif, $weight recu");};
    }

    /** Doit renvoyer poids × multiplicateur de rareté (règle du jeu, arbitraire). Niveau 4. */
    public function value(): float
    {
        throw new \LogicException('À implémenter');
    }

    /** Chaque type d'objet se décrit à sa façon : c'est aux sous-classes de l'écrire. */
    abstract public function describe(): string;

    /** Doit renvoyer la même chose que describe() : c'est le contrat Stringable. Niveau 4. */
    public function __toString(): string
    {
        throw new \LogicException('À implémenter');
    }
}
