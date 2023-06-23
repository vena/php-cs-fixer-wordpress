<?php
/**
 * Enforces the correct spelling of `WordPress` in strings and comments.
 * Based heavily on the official WordPress CapitalPDangitSniff for PHPCS.
 * https:// github.com/WordPress/WordPress-Coding-Standards/blob/ac0a94c2f831b9adb633e7af5f000d6a0df650f9/WordPress/Sniffs/WP/CapitalPDangitSniff.php.
 */

namespace vena\WordPress\PhpCsFixer\Fixer;

use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\CT;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;
use vena\WordPress\PhpCsFixer\BaseAbstractFixer;
use vena\WordPress\PhpCsFixer\TokenUtils;

final class WordPressCapitalPDangitFixer extends BaseAbstractFixer {
	public const WP_REGEX = '#(?<![\\\\/\$@`-])\b(Word[ _-]*Pres+)\b(?![@/`-]|\.(?:org|com|net|test|tv)|[^\s<>\'"()]*?\.(?:php|js|css|png|j[e]?pg|gif|pot))#i';

	private $strings_and_comments = array();

	public function __construct() {
		$this->strings_and_comments = array_merge(
			TokenUtils::$comments,
			TokenUtils::$textString,
		);
		parent::__construct();
	}

	/** {@inheritDoc} */
	public function getDefinition(): FixerDefinitionInterface {
		return new FixerDefinition(
			'WordPress must have a capital W and P.',
			array(
				new CodeSample(
					"<?php echo 'wordpress';\n"
				),
			),
			null,
			"Risky when 'wordpress' is used outside of comments and strings."
		);
	}

	/** {@inheritDoc} */
	public function getName(): string {
		return 'Vena/wp_capital_p_dangit';
	}

	/**
	 * {@inheritDoc}
	 *
	 * Should run last
	 * */
	public function getPriority(): int {
		return \PHP_INT_MAX * -1;
	}

	/** {@inheritDoc} */
	public function isRisky(): bool {
		return true;
	}

	/** {@inheritDoc} */
	public function isCandidate( Tokens $tokens ): bool {
		$candidates = $this->strings_and_comments;

		return $tokens->isAnyTokenKindsFound( $candidates );
	}

	/** {@inheritDoc} */
	public function applyFix( \SplFileInfo $file, Tokens $tokens ): void {
		foreach ( $tokens as $index => $token ) {
			if (
				! $token->isGivenKind( $this->strings_and_comments  )
			) {
				continue;
			}

			$prevToken = $tokens->getPrevMeaningfulToken( $index );
			if ( $prevToken ) {
				$prevToken = $tokens[ $prevToken ];
				if ( $prevToken->equalsAny( array( '[', array( CT::T_ARRAY_INDEX_CURLY_BRACE_OPEN ) ) ) ) {
					continue;
				}
			}

			if ( $token->isGivenKind( array( \T_START_HEREDOC ) ) ) {
				$blockEnd = $tokens->getNextTokenOfKind( $index, array( array( \T_END_HEREDOC ) ) );
				for ( $i = $index; $i < $blockEnd; ++$i ) {
					$this->operateOnToken( $i, $tokens );
				}

				continue;
			}

			$this->operateOnToken( $index, $tokens );
		}
	}

	protected function operateOnToken( int $index, Tokens $tokens ): void {
		$token = $tokens[ $index ];

		$content = $token->getContent();

		if ( preg_match_all( self::WP_REGEX, $content, $matches, ( \PREG_PATTERN_ORDER | \PREG_OFFSET_CAPTURE ) ) > 0 ) {

			// Prevent false positives
			$offset = 0;
			foreach ( $matches[1] as $key => $match_data ) {
				$next_offset = ( $match_data[1] + mb_strlen( $match_data[0] ) );

				// Prevent matches on part of a URL
				if ( 1 === preg_match( '`http[s]?://[^\s<>\'"()]*' . preg_quote( $match_data[0], '`' ) . '`', $content, $discard, 0, $offset ) ) {
					unset( $matches[1][ $key ] );
				} elseif ( 1 === preg_match( '`[a-z]+=(["\'])' . preg_quote( $match_data[0], '`' ) . '\1`', $content, $discard, 0, $offset ) ) {
					// Prevent matches on html attributes like: `value="wordpress"`.
					unset( $matches[1][ $key ] );
				} elseif ( 1 === preg_match( '`\\\\\'' . preg_quote( $match_data[0], '`' ) . '\\\\\'`', $content, $discard, 0, $offset ) ) {
					// Prevent matches on xpath queries and such: `\'wordpress\'`.
					unset( $matches[1][ $key ] );
				} elseif ( 1 === preg_match( '`(?:\?|&amp;|&)[a-z0-9_]+=' . preg_quote( $match_data[0], '`' ) . '(?:&|$)`', $content, $discard, 0, $offset ) ) {
					// Prevent matches on url query strings: `?something=wordpress`.
					unset( $matches[1][ $key ] );
				}

				$offset = $next_offset;
			}

			if ( empty( $matches[1] ) ) {
				return;
			}

			$misspelled = $this->retrieve_misspellings( $matches[1] );

			if ( empty( $misspelled ) ) {
				return;
			}

			$replacement = $content;
			foreach ( $matches[1] as $match ) {
				$replacement = substr_replace( $replacement, 'WordPress', $match[1], mb_strlen( $match[0] ) );
			}

			$newToken    = $token->getPrototype();
			$newToken[1] = $replacement;
			$tokens->overrideRange( $index, $index, array( new Token( $newToken ) ) );
		}
	}

	protected function retrieve_misspellings( $match_stack ) {
		$misspelled = array();
		foreach ( $match_stack as $match ) {
			if ( is_array( $match ) ) {
				$match = $match[0];
			}
			if ( 'WordPress' !== $match ) {
				$misspelled[] = $match;
			}
		}

		return $misspelled;
	}
}
