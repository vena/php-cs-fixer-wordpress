<?php

namespace Tests\Fixer;

/**
 * @internal
 *
 * @covers \vena\WordPress\PhpCsFixer\Fixer\WordPressArrayIndexSpacesFixer
 */
final class WordPressArrayIndexSpacesFixerTest extends AbstractFixerTestCase {
	/** @dataProvider provideFixCases */
	public function testFix( string $expected, ?string $input = null ): void {
		$this->doTest( $expected, $input );
	}

	public function provideFixCases(): iterable {
		yield 'must not add spaces to numeric keys' => array(
			'<?php echo $var[1];',
		);

		yield 'must not add spaces to string keys' => array(
			'<?php echo $var["test"];',
		);

		yield 'must not add spaces to push index' => array(
			'<?php $var[] = 1;',
		);

		$spaces = array(
			'$i',
			'strtoupper($i)',
			'className::method()',
		);

		foreach ( $spaces as $t ) {
			yield array(
				sprintf( '<?php echo $var[ %s ];', $t ),
				sprintf( '<?php echo $var[%s];', $t ),
			);
		}

		yield 'must remove space' => array(
			'<?php echo $var[1];',
			'<?php echo $var[ 1 ];',
		);
	}
}
