<?php

declare(strict_types=1);

namespace Dungeon;

/**
 * Le personnage joueur. Niveau 1, complété au chapitre Encapsulation puis aux niveaux 2, 3 et 4.
 * Fighter (niveau 4) : le contrat commun avec les monstres.
 *
 * Pas de getter ici : les propriétés se lisent directement ($hero->hp). Ce qui
 * empêche l'extérieur de les écrire, c'est `private(set)` (ou `readonly`).
 */
final class Hero implements Fighter
{
    /** Le maximum de points de vie. Lecture publique, écriture réservée à la classe. Niveau 1. */
    public private(set) int $maxHp = 0;

    /**
     * Les points de vie courants. Lecture publique, écriture réservée à la classe. Niveau 1.
     * Chapitre Encapsulation : ajouter un hook `set` qui borne la valeur entre 0 et $maxHp,
     * pour que takeDamage() et heal() n'aient plus à s'en soucier.
     */
    public private(set) int $hp = 0;

    /**
     * Propriété virtuelle (hook `get`, rien n'est stocké) : doit valoir true quand
     * hp est égal à maxHp. Chapitre Encapsulation.
     */
    public bool $isFullHealth {
        get => throw new \LogicException('À implémenter');
    }

    /** Le sac, créé dans le constructeur : composition. Jamais remplacé, donc readonly. Niveau 2. */
    public readonly Inventory $inventory;

    /** L'arme équipée, ou null si le héros se bat à mains nues. Écrite par equip() seulement. Niveau 3. */
    public private(set) ?Weapon $weapon = null;

    /**
     * Doit initialiser maxHp et hp, et créer l'inventaire du héros (niveau 2).
     * Chapitre Encapsulation : doit d'abord refuser un nom vide
     * (InvalidArgumentException('Un héros a un nom.')) et un maxHp inférieur à 1
     * (InvalidArgumentException("maxHp doit valoir au moins 1, $maxHp reçu.")).
     */
    public function __construct(
        public readonly string $name,
        int $maxHp = 10,
        public readonly int $strength = 2,
    ) {
        $this->maxHp = $maxHp;
        $this->hp = $maxHp;
    }

    /** Doit retirer $amount points de vie, sans jamais descendre sous 0. */
    public function takeDamage(int $amount): void
    {
        $this->hp = max(0, $this->hp - $amount);
    }

    /** Doit rendre $amount points de vie, sans jamais dépasser $maxHp. */
    public function heal(int $amount): void
    {
        $this->hp = min($this->maxHp, $this->hp + $amount);
    }

    /** Doit renvoyer true tant qu'il reste au moins 1 point de vie. */
    public function isAlive(): bool
    {
        return $this->hp > 0;
    }

    /** Doit équiper l'arme passée en paramètre (elle remplace la précédente). Niveau 3. */
    public function equip(Weapon $weapon): void
    {
        throw new \LogicException('À implémenter');
    }

    /** Doit soigner le héros du montant de la potion, puis retirer la potion de l'inventaire. Niveau 3. */
    public function drink(Potion $potion): void
    {
        throw new \LogicException('À implémenter');
    }

    /** Doit renvoyer strength, plus les dégâts de l'arme équipée s'il y en a une. */
    public function attack(): int
    {
        return $this->strength;
    }

    /** Doit renvoyer "Arthur (7/10 PV)". */
    public function __toString(): string
    {
        return sprintf('%s (%d/%d PV)', $this->name, $this->hp, $this->maxHp);
    }
}
