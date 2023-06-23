<?php
/**
 * Add a single space between brackets when array access includes a variable.
 */

namespace vena\WordPress\PhpCsFixer\Fixer;

use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\Tokens;
use vena\WordPress\PhpCsFixer\BaseAbstractFixer;
use vena\WordPress\PhpCsFixer\TokenUtils;

final class WordPressArrayIndexSpacesFixer extends BaseAbstractFixer {
	private $safeContent = array(
		\T_CONSTANT_ENCAPSED_STRING,
		\T_LNUMBER,
		\T_CONST,
	);

	/** {@inheritDoc} */
	public function getDefinition(): FixerDefinitionInterface {
		return new FixerDefinition(
			'Ensure array indices which contain variables include a space after the opening and before the closing bracket.',
			array(
				new CodeSample(
					'<?php echo $arr[$index];' . "\n"
				),
			)
		);
	}

	/** {@inheritDoc} */
	public function getName(): string {
		return 'Vena/wp_array_index_spaces';
	}

	/**
	 * {@inheritDoc}
	 *
	 * Must run last
	 * */
	public function getPriority(): int {
		return \PHP_INT_MAX * -1;
	}

	/** {@inheritDoc} */
	public function isCandidate( Tokens $tokens ): bool {
		return $tokens->isTokenKindFound( '[' );
	}

	/** {@inheritDoc} */
	public function applyFix( \SplFileInfo $file, Tokens $tokens ): void {
		foreach ( $tokens as $index => $token ) {
			if ( ! $token->equals( '[' ) ) {
				continue;
			}

			$previous = $tokens->getPrevMeaningfulToken( $index );

			// Only act if previous token is a variable
			if ( ! $previous || ! $tokens[ $previous ]->isGivenKind( \T_VARIABLE ) ) {
				continue;
			}

			$next = $tokens->getNextMeaningfulToken( $index );

			// Empty indexes, array push, should have no space
			if ( ! $next || $tokens[ $next ]->equals( ']' ) ) {
				$tokens->removeTrailingWhitespace( $index );

				continue;
			}

			$blockStart = $index;
			$blockEnd   = $tokens->findBlockEnd( Tokens::BLOCK_TYPE_INDEX_SQUARE_BRACE, $blockStart );

			$content = $tokens->findGivenKind(
				$this->safeContent,
				$blockStart,
				$blockEnd
			);

			$hasIgnored = false;
			foreach ( $this->safeContent as $ignore ) {
				if ( count( $content[ $ignore ] ) ) {
					$hasIgnored = true;
				}
			}

			// if content is "safe", it should not have spaces.
			if ( $hasIgnored ) {
				$tokens->removeLeadingWhitespace( $blockEnd );
				$tokens->removeTrailingWhitespace( $blockStart );

				continue;
			}

			TokenUtils::addSpaces( $tokens, $blockStart, $blockEnd );
		}
	}
}
