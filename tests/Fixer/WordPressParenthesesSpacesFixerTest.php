<?php

namespace Tests\Fixer;

/**
 * @internal
 *
 * @covers \vena\WordPress\PhpCsFixer\Fixer\WordPressParenthesesSpacesFixer
 */
final class WordPressParenthesesSpacesFixerTest extends AbstractFixerTestCase {
	/** @dataProvider provideFixCases */
	public function testFix( string $expected, ?string $input = null ): void {
		$this->doTest( $expected, $input );
	}

	public function provideFixCases(): iterable {
		$shouldNotAlter = array(
			'casts'                       => '<?php (string) $i;',
			'casts in control structures' => '<?php foreach ( (array) $bar as $foo ) {}',
		);

		foreach ( $shouldNotAlter as $name => $sna ) {
			yield $name => array( $sna );
		}

		yield 'function calls' => array(
			'<?php my_function( $i, $j );',
			'<?php my_function($i, $j);',
		);

		yield 'function defs' => array(
			'<?php function test( $i ) {}',
			'<?php function test($i) {}',
		);

		yield 'conditionals' => array(
			'<?php if ( $i ) {}',
			'<?php if ($i) {}',
		);

		yield 'complex conditional' => array(
			'<?php if ( $i && ( $j || $k ) ) {}',
			'<?php if ($i && ($j || $k)) {}',
		);

		yield 'control structures' => array(
			'<?php foreach ( (array) $bar as $foo ) {}',
			'<?php foreach ((array) $bar as $foo) {}',
		);
	}
}
