<?php
/**
 * Add a single space between non-empty parentheses and
 * array access using variables ( $array[$index] ).
 */

namespace vena\WordPress\PhpCsFixer\Fixer;

use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\Tokens;
use vena\WordPress\PhpCsFixer\BaseAbstractFixer;
use vena\WordPress\PhpCsFixer\TokenUtils;

final class WordPressParenthesesSpacesFixer extends BaseAbstractFixer {
	private $ignoreTokens = array(
		array( \T_VARIABLE ),
		')',
		'}',
		']',
		array( \T_USE ),
		array( \T_THROW ),
		array( \T_YIELD ),
		array( \T_YIELD_FROM ),
		array( \T_CLONE ),
	);

	/** {@inheritDoc} */
	public function getDefinition(): FixerDefinitionInterface {
		return new FixerDefinition(
			'Parentheses must include a single space after the opening and before the closing parenthesis unless empty.',
			array(
				new CodeSample( "<?php\nif (\$a) {\nfoo();\n}\n" ),
			)
		);
	}

	/** {@inheritDoc} */
	public function getName(): string {
		return 'Vena/wp_parentheses_spaces';
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
		return $tokens->isAnyTokenKindsFound( array( '(', ')' ) );
	}

	/** {@inheritDoc} */
	public function applyFix( \SplFileInfo $file, Tokens $tokens ): void {
		foreach ( $tokens as $index => $token ) {
			if ( ! $token->equalsAny( array( '(', ')' ) ) || $token->isComment() ) {
				continue;
			}

			$blockStart = $index;

			if ( ! $token->equals( '(' ) ) {
				$blockStart = $tokens->findBlockStart( Tokens::BLOCK_TYPE_PARENTHESIS_BRACE, $index );
			}
			$blockEnd = $tokens->findBlockEnd( Tokens::BLOCK_TYPE_PARENTHESIS_BRACE, $blockStart );

			$contents = TokenUtils::getBlockContent( $blockStart, $blockEnd, $tokens );
			$owner    = $tokens->getPrevMeaningfulToken( $blockStart );

			if ( ! $owner ) {
				continue;
			}

			$owner = $tokens[ $owner ];

			// Check if owner is an ignored token
			if ( $owner->equalsAny( $this->ignoreTokens ) ) {
				continue;
			}

			// Parentheses must have non-whitespace content to act
			if ( 0 === count( $contents ) || mb_strlen( trim( join( $contents ) ) ) < 1 ) {
				// Remove any possible whitespace between empties
				$tokens->removeTrailingWhitespace( $blockStart );

				continue;
			}

			TokenUtils::addSpaces( $tokens, $blockStart, $blockEnd );
		}
	}
}
