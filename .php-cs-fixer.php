<?php

require __DIR__ . '/includes.php';

$config = new PhpCsFixer\Config();
$RuleSet = new vena\WordPress\PhpCsFixer\WordPressSet();

return $config
	->registerCustomFixers( $RuleSet->getCustomFixers() )
	->registerCustomFixers( array(
		new vena\WordPress\PhpCsFixer\Fixer\WordPressCapitalPDangitFixer(),
	) )
	->setRiskyAllowed( $RuleSet->isRisky() )
	->setIndent( "\t" )
	->setRules( array_merge(
		$RuleSet->getRules(),
		array(
			// 'Vena/wp_capital_p_dangit' => true,
		)
	) )
	->setFinder(
		PhpCsFixer\Finder::create()
			->exclude( 'vendor' )
			->exclude( 'tests' )
			->in( __DIR__ )
	)
;
