<?php

namespace Tests\Fixer;

/**
 * @internal
 * @covers \vena\WordPress\PhpCsFixer\Fixer\WordPressMultilineAssocArrayFixer
 */
final class WordPressMultilineAssocArrayFixerTest extends AbstractFixerTestCase {
	/** @dataProvider provideFixCases */
	public function testFix( string $expected, ?string $input = null ): void {
		$this->doTest( $expected, $input );
	}

	public function provideFixCases(): iterable {
		$shouldNotAlter = array(
			'single line array' => '<?php $i = [1, 2, 3];',
			'array with single assoc item' => '<?php $i = [ "testing" => "test" ];'
		);

		foreach ( $shouldNotAlter as $name => $sna ) {
			yield $name => array( $sna );
		}

		yield 'associative array with multiple items' => array(
<<<FOO
<?php
\$i = [
'one' => 'one',
'two' => 'two',

// Comment!
'three' => 'three'
];
FOO,
<<<BAR
<?php
\$i = [ 'one' => 'one', 'two' => 'two',

// Comment!
'three' => 'three' ];
BAR
		);

		yield 'associative array with mixed items' => array(
<<<FOO
<?php \$i = [
1,
2,
'three' => 'three'
];
FOO,
<<<BAR
<?php \$i = [1, 2, 'three' => 'three' ];
BAR
		);

	}
}
