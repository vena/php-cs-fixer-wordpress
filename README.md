# WordPress php-cs-fixer ruleset

This package contains a Frankenstein's monster of rules and fixers for [PHP CS Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer) which attempt to satisfy most of the WordPress Coding Standards. It builds upon the @PSR2 and @PhpCsFixer rulesets, along with custom fixers for some WordPress peculiarities.

WPCS rules not covered by this package include, but are not limited to:
* Filename conventions
* Tabs vs Spaces
* Nearly all errors and warnings which are not automatically fixable

Yoda style looks to no longer be required in WPCS 3.0, so it is not required in this ruleset.

I use PHP CS Fixer because I find it easier to integrate into my workflow than PHP_CodeSniffer. If you are not me, and especially if you require WPCS compliance, I highly suggest that you stick with [PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) and [the official WPCS sniffs](https://github.com/WordPress/WordPress-Coding-Standards).

## Use

```php
// In case of not using autoload...
require __DIR__ . '/includes.php';

$config = new PhpCsFixer\Config();
$RuleSet = new vena\WordPress\PhpCsFixer\WordPressRuleSet();

return $config
	->registerCustomFixers( $RuleSet->getCustomFixers() )
	// OPTIONAL. See below.
	->registerCustomFixers( array(
		new vena\WordPress\PhpCsFixer\Fixer\WordPressCapitalPDangitFixer(),
	) )
	->setRiskyAllowed( $RuleSet->isRisky() )
	->setIndent( "\t" )
	->setRules( array_merge(
		$RuleSet->getRules(),
		array(
			// OPTIONAL. See below.
			'Vena/wp_capital_p_dangit' => true,
		)
	) )
	->setFinder(
		PhpCsFixer\Finder::create()
			->exclude( 'vendor' )
			->in( __DIR__ )
	)
;
```

## Custom Fixers

### WordPressParenthesesSpacesFixer

With few exceptions, WPCS specifies spaces after opening and before closing parenthesis.

### WordPressArrayIndexSpacesFixer

If a variable is used as an array key, it must be flanked by spaces. All other keys should not have spaces.

### WordPressMultilineAssocArrayFixer

For any array which contains multiple items and any associative key, each item must appear on a new line. NOTE: This fixer DOES NOT fix indentation, and should be used with the `array_indentation` rule.

### WordPressCapitalPDangitFixer

Attempts to automatically correct common misspellings of WordPress in strings and comments. Its rule, `Vena/wp_capital_p_dangit`, is not included in the `RuleSet::getRules()` helper, and must be added to your config if you intend to use it. Unlike the official WPCS sniff, this does not care about class names. NOTE: This is a risky fixer that may not accurately distinguish safe transforms.

## Credits

[PHP CS Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer)

[WordPress Coding Standards](https://github.com/WordPress/WordPress-Coding-Standards).

Test scaffolding is built on the work of Kuba Werłos' [PHP CS Fixer: custom fixers](https://github.com/kubawerlos/php-cs-fixer-custom-fixers/)