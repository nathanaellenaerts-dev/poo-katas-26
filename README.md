[![Tests](https://github.com/nathanaellenaerts-dev/poo-katas-26/actions/workflows/tests.yml/badge.svg)](https://github.com/nathanaellenaerts-dev/poo-katas-26/actions/workflows/tests.yml)
# Les katas du Donjon (poo-katas-26)

Les exercices du bloc POO de 5XCOS. **Un groupe de tests = un chapitre.** Vous ne
partez pas d'une page blanche : les classes sont déjà déclarées dans `src/`, avec
les bonnes signatures. Les méthodes, elles, lèvent toutes `LogicException('À implémenter')`.
Votre travail : remplacer ces lignes par du vrai code, jusqu'à ce que les tests
du groupe passent au vert.

Les tests sont déjà écrits. **Ils sont l'énoncé.** Un test rouge vous dit
exactement ce qu'on attend.

Il faut **PHP 8.4 ou plus** : le chapitre Encapsulation s'appuie sur
`private(set)` et sur les *property hooks*, qui n'existent pas avant.

## Les groupes, dans l'ordre

| Groupe | Chapitre | Ce que vous écrivez | Notions |
|---|---|---|---|
| `niveau-1` | 1 — Objets et classes | `Dice`, `Hero` | classe, `new`, `$this`, constructeur avec promotion, `readonly`, `public private(set)`, `__toString()`, méthode `static` |
| `encapsulation` | 3 — Encapsulation | validation dans les constructeurs de `Dice` et `Hero`, hook `set` sur `Hero::$hp`, propriété virtuelle `Hero::$isFullHealth` | invariant, `InvalidArgumentException`, visibilité asymétrique, hooks `get` et `set` |
| `niveau-2` | 5 — Relations entre objets | `Item`, `Inventory`, `Hero::$inventory` | propriété typée par une classe, tableau d'objets, composition (le sac est créé dans le constructeur du héros) |
| `niveau-3` | 6 — Héritage et polymorphisme | `Weapon`, `Potion`, `Monster`, `Goblin`, `Dragon`, `InventoryFullException`, `Hero::equip()`, `Hero::drink()` | `extends`, `abstract`, `parent::__construct()`, redéfinition, `?Weapon`, exceptions personnalisées |
| `niveau-4` | 7 — Contrats | `Rarity`, `Fighter`, **extraire le trait `HasHealth`** (déplacer `$hp`, `$maxHp`, `$isFullHealth`, `takeDamage()`, `heal()`, `isAlive()` hors de `Hero` et `Monster`), `Item implements Stringable`, `Inventory implements Countable, IteratorAggregate` | enum, interface, trait, interfaces natives de PHP |
| `niveau-5` (bonus) | 7 — Contrats | `Battle` | typer par une interface, recevoir une dépendance au lieu de la fabriquer |

## La procédure, pas à pas

1. **Forkez** ce dépôt sur votre compte GitHub (bouton *Fork* en haut à droite).
2. **Clonez** votre fork :
   ```bash
   git clone https://github.com/VOTRE-COMPTE/poo-katas-26.git
   cd poo-katas-26
   ```
3. **Installez** les dépendances (Pest) :
   ```bash
   composer install
   ```
4. **Lancez tout** une fois, pour voir l'ampleur du chantier :
   ```bash
   composer test
   ```
   Tout est rouge. C'est normal, c'est le point de départ.
5. **Travaillez un groupe à la fois**, dans l'ordre du tableau :
   ```bash
   composer test -- --group=niveau-1
   composer test -- --group=encapsulation
   composer test -- --group=niveau-2
   ```
6. **Lisez le premier test rouge.** Pest affiche le nom du test, le fichier, la
   ligne, la valeur attendue et la valeur obtenue. Ouvrez la classe concernée
   dans `src/`, supprimez le `throw new \LogicException('À implémenter');`,
   écrivez le code. Relancez. Vert.
7. **Committez et poussez** dès qu'un groupe est vert :
   ```bash
   git add .
   git commit -m "niveau 1 vert"
   git push
   ```
8. **Regardez l'onglet Actions** de votre fork sur GitHub : six jobs
   (`niveau-1`, `encapsulation`, `niveau-2` … `niveau-5`), une coche verte ou une
   croix rouge pour chacun. C'est votre tableau de bord : il ne ment pas et il ne
   juge pas.

Pour ne relancer qu'un seul test pendant que vous cherchez :

```bash
vendor/bin/pest --filter="heal ne dépasse jamais"
```

## Comment lire `src/`

Chaque membre porte un commentaire d'une ou deux lignes qui dit **ce qu'il doit
faire**, et le groupe auquel il arrive. Exemple :

```php
/** Doit renvoyer un entier tiré au hasard entre 1 et $sides inclus. */
public function roll(): int
{
    throw new \LogicException('À implémenter');
}
```

Ne changez pas les signatures (noms, types, paramètres) ni la visibilité des
propriétés : les tests s'appuient dessus. Vous pouvez en revanche ajouter des
méthodes privées si ça vous aide.

## Quatre détails qui surprennent (et qui sont voulus)

**1. Il n'y a aucun getter.** Pas de `hp()`, pas de `name()` : on lit `$hero->hp`
et `$hero->name` directement. Ce qui empêche l'extérieur de les modifier, c'est la
déclaration : `readonly` pour ce qui est fixé à la création (`$name`, `$sides`),
`public private(set)` pour ce que seule la classe écrit (`$hp`, `$maxHp`,
`$weapon`). Les tests vérifient que `$hero->hp = 5` lève une `Error`. Ce qui
reste une méthode, c'est ce qui **agit** (`takeDamage()`, `equip()`) ou ce qui
**calcule** (`totalWeight()`, `isAlive()`).

**2. `Item` est déjà abstraite, dès le niveau 2.** Le repo ne contient qu'un seul
`src/`, celui de l'état final. Or au chapitre 6, `Item` devient abstraite : on ne
ramasse jamais « un objet », on ramasse une arme ou une potion. Pour que les
tests du niveau 2 restent valables jusqu'au bout, ils utilisent
`tests/Support/SimpleItem.php`, une petite sous-classe concrète qui n'existe que
pour les tests. Vous n'avez rien à y faire : implémentez `Item` normalement.

**3. Le sac trop plein : `false` au niveau 2, exception au niveau 3.** Le
chapitre 5 vous fait renvoyer `false` quand l'objet ne rentre pas ; le chapitre 6
remplace ça par `throw new InventoryFullException(...)`. Le test du niveau 2 est
écrit pour accepter **les deux** comportements — il vérifie surtout que le sac
reste vide. Le test du niveau 3, lui, exige l'exception. Vous ne cassez donc rien
en faisant l'évolution demandée.

**4. `$hp` et `$maxHp` sont écrits deux fois, dans `Hero` et dans `Monster`.** C'est
voulu, et c'est même le sujet d'un exercice : au niveau 1 vous écrivez le code des
points de vie dans `Hero`, au chapitre Encapsulation vous y ajoutez le hook `set`
qui borne `$hp` entre 0 et `$maxHp` (après quoi `takeDamage()` se résume à
`$this->hp -= $amount;`), au niveau 3 vous réécrivez tout ça dans `Monster`, et au
niveau 4 vous en avez assez — vous le déplacez une bonne fois dans le trait
`src/HasHealth.php` (livré vide) et vous mettez `use HasHealth;` dans les deux
classes. La duplication d'abord, le trait ensuite : sinon le trait ne règle aucun
problème que vous auriez vraiment rencontré.

## Ce qui est dans le dossier

| Fichier | Rôle |
|---|---|
| `composer.json` | Dépendances (Pest), PHP ≥ 8.4, autoload PSR-4 `Dungeon\` → `src/`, script `composer test`. |
| `phpunit.xml` | Configuration du lanceur de tests. |
| `src/` | Les squelettes à compléter : c'est **le seul dossier que vous modifiez**. |
| `tests/Niveau1Test.php` … `Niveau5Test.php` | Les énoncés des niveaux, un fichier par niveau, chaque test marqué `->group('niveau-N')`. **Ne les modifiez pas.** |
| `tests/EncapsulationTest.php` | L'énoncé du chapitre Encapsulation, groupe `encapsulation`, à faire entre les niveaux 1 et 2. |
| `tests/Support/SimpleItem.php` | Sous-classe concrète d'`Item` pour les tests du niveau 2. |
| `tests/Support/FixedDice.php` | Un dé truqué qui déroule une série fixe, pour rendre le combat du niveau 5 reproductible. |
| `tests/Pest.php` | Configuration de Pest. |
| `.github/workflows/tests.yml` | La CI : un job par groupe, six résultats visibles dans l'onglet Actions. |

## Besoin d'un exemple ?

Le Donjon complet et corrigé jusqu'au niveau 2 est ici :
[`poo-exemple-26`](https://github.com/opmvpc/poo-exemple-26). C'est celui qu'on
lit ensemble en séance 1.
