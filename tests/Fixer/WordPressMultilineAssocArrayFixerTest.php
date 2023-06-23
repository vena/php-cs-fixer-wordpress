<?php

namespace Tests\Fixer;

/**
 * @internal
 *
 * @covers \vena\WordPress\PhpCsFixer\Fixer\WordPressMultilineAssocArrayFixer
 */
final class WordPressMultilineAssocArrayFixerTest extends AbstractFixerTestCase {
	/** @dataProvider provideFixCases */
	public function testFix( string $expected, ?string $input = null ): void {
		$this->doTest( $expected, $input );
	}

	public function provideFixCases(): iterable {
		$shouldNotAlter = array(
			'single line array'                 => '<?php $i = [1, 2, 3];',
			'array with single assoc item'      => '<?php $i = [ "testing" => "test" ];',
			'array with string members'         => '<?php $i = array( "test", "testing", );',
			'array with string and var members' => '<?php $i = array( "test", $testing );',
			'deep array with string members'    => <<<'FOO'
			<?php
			$i = array(
				'test' => 'test',
				'test2' => array( 'deep1', 'deep2', )
			);
FOO,
			'function call' => '<?php $test = \date( \'r\', $testing );',
		);

		foreach ( $shouldNotAlter as $name => $sna ) {
			yield $name => array( $sna );
		}

		yield 'associative array with multiple items' => array(
			<<<'FOO'
<?php
$i = [
'one' => 'one',
'two' => 'two',

// Comment!
'three' => 'three'
];
FOO,
			<<<'BAR'
<?php
$i = [ 'one' => 'one', 'two' => 'two',

// Comment!
'three' => 'three' ];
BAR
		);

		yield 'associative array with mixed items' => array(
			<<<'FOO'
<?php $i = [
1,
2,
'three' => 'three'
];
FOO,
			<<<'BAR'
<?php $i = [1, 2, 'three' => 'three' ];
BAR
		);

		yield 'deep array with multiple items' => array(
			<<<'FOO'
<?php
$i = array(
'test' => 'test',
'test2' => array(
'deep1' => 'test',
'deep2' => 'test',
)
);
FOO,
			<<<'BAR'
<?php
$i = array(
'test' => 'test',
'test2' => array( 'deep1' => 'test', 'deep2' => 'test', )
);
BAR
		);
	}
}
