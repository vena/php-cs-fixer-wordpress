<?php
/**
 * For arrays with multiple items and any associative keys, each item must be on a new line.
 */

namespace vena\WordPress\PhpCsFixer\Fixer;

use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\CT;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;
use PhpCsFixer\Tokenizer\TokensAnalyzer;
use SplFileInfo;
use vena\WordPress\PhpCsFixer\BaseAbstractFixer;

final class WordPressMultilineAssocArrayFixer extends BaseAbstractFixer {
	/** {@inheritDoc} */
	public function getDefinition(): FixerDefinitionInterface {
		return new FixerDefinition(
			'For arrays with multiple items and any associative keys, each item must be on a new line.',
			array(
				new CodeSample(
					"<?php\n\$i = ['index1' => 'item', 'index2' => 'item2'];\n"
				),
			)
		);
	}

	/** {@inheritDoc} */
	public function getName(): string {
		return 'Vena/wp_multiline_assoc_arrays';
	}

	/**
	 * {@inheritDoc}
	 *
	 * Must run before array_indentation
	 * */
	public function getPriority(): int {
		return 30;
	}

	/** {@inheritDoc} */
	public function isCandidate( Tokens $tokens ): bool {
		return $tokens->isAnyTokenKindsFound( array( \T_ARRAY, CT::T_ARRAY_SQUARE_BRACE_OPEN ) );
	}

	/** {@inheritDoc} */
	public function applyFix( SplFileInfo $file, Tokens $tokens ): void {
		$tokensAnalyzer = new TokensAnalyzer( $tokens );
		$reverseTokens = array_reverse( $tokens->toArray(), true );
		$blocks = array();
		foreach ( $reverseTokens as $index => $token ) {
			if ( ! $token->isGivenKind( array( \T_ARRAY, CT::T_ARRAY_SQUARE_BRACE_OPEN ) ) ) {
				continue;
			}

			$edgeDef = Tokens::BLOCK_TYPE_ARRAY_SQUARE_BRACE;
			$blockStart = $index;

			if ( $token->isGivenKind( \T_ARRAY ) ) {
				$edgeDef = Tokens::BLOCK_TYPE_PARENTHESIS_BRACE;
				$blockStart = $tokens->getNextMeaningfulToken( $index );
			}

			$blockEnd = $tokens->findBlockEnd( $edgeDef, $blockStart );

			// Skip non-associative arrays
			$isAssoc = count( $tokens->findGivenKind( \T_DOUBLE_ARROW, $blockStart, $blockEnd ) );
			if ( ! $isAssoc ) {
				continue;
			}

			$blocks[ ] = array(
				'type' => $edgeDef,
				'start' => $blockStart,
				'end' => $blockEnd,
			);
		}

		foreach ( $blocks as $block ) {
			$blockAffected = false;
			for ( $i = $block['end']; $i >= $block['start']; --$i ) {
				if ( $tokens[ $i ]->getContent() !== ',' ) {
					continue;
				}

				$nextPos = $i + 1;
				$nextToken = $tokens[ $nextPos ] ?? null;
				if ( ! $nextToken instanceof Token ) {
					continue;
				}

				if ( \str_contains( $nextToken->getContent(), "\n" ) ) {
					continue;
				}

				$sibling = $tokens->getNonWhitespaceSibling( $i, 1, " \t\r\0\x0B" );
				if ( $sibling !== null && $tokens[ $sibling ]->isGivenKind( \T_COMMENT ) ) {
					continue;
				}

				$blockAffected = true;
				$tokens->removeTrailingWhitespace( $index );
				$tokens->ensureWhitespaceAtIndex( $nextPos, 0, "\n" );
			}
			if ( $blockAffected ) {
				// Find our new block end
				$block['end'] = $tokens->findBlockEnd( $block['type'], $block['start'] );

				// Ensure newlines at the start and end of the block.
				$tokens->removeLeadingWhitespace( $block['end'] );
				$tokens->ensureWhitespaceAtIndex( $block['end'], 0, "\n" );
				$tokens->removeTrailingWhitespace( $block['start'] );
				$tokens->ensureWhitespaceAtIndex( $block['start'], 1, "\n" );
			}
		}
	}
}
