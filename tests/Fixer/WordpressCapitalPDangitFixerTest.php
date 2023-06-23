<?php

namespace Tests\Fixer;

/**
 * @internal
 *
 * @covers \vena\WordPress\PhpCsFixer\Fixer\WordPressCapitalPDangitFixer
 */
final class WordPressCapitalPDangitFixerTest extends AbstractFixerTestCase {
	/** @dataProvider provideFixCases */
	public function testFix( string $expected, ?string $input = null ): void {
		$this->doTest( $expected, $input );
	}

	public function provideFixCases(): iterable {
		$shouldNotAlter = array(
			'function name'         => '<?php function testing_wordpress() {}',
			'function property'     => '<?php function testing( $wordpress) {}',
			'false positive: email' => '<?php echo "bob@wordpress.com";',
			'false positive: url'   => '<?php // https://www.wordpress.com?i=wordpress',
			'array keys'            => '<?php echo $arr["wordpress"];',
			'html attributes'       => '<span class="wordpress"></span>',
		);

		foreach ( $shouldNotAlter as $name => $sna ) {
			yield sprintf( 'Must not alter: %s', $name ) => array( $sna );
		}

		yield 'Must alter: inside strings' => array(
			'<?php echo "WordPress";',
			'<?php echo "wordpress";',
		);

		yield 'Must alter: in double-slash comments' => array(
			'<?php // WordPress',
			'<?php // wordpress',
		);

		yield 'Must alter: in slash-star comments' => array(
			'<?php /* WordPress */',
			'<?php /* wordpress */',
		);

		yield 'Must alter: inside HEREDOC' => array(
			<<<'FOO'
<?php
echo <<<HEREDOC
WordPress
${variable['wordpress']}
HEREDOC;
FOO,
			<<<'BAR'
<?php
echo <<<HEREDOC
wordpress
${variable['wordpress']}
HEREDOC;
BAR
		);

		yield 'Must alter: inside NOWDOC' => array(
			<<<'FOO'
<?php
echo <<<'NOWDOC'
WordPress
NOWDOC;
FOO,
			<<<'BAR'
<?php
echo <<<'NOWDOC'
wordpress
NOWDOC;
BAR
		);
	}
}
