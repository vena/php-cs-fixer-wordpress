<?php

namespace vena\WordPress\PhpCsFixer;

use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;

class TokenUtils {
	public static $comments = array(
		\T_COMMENT,
		\T_DOC_COMMENT,
	);

	public static $string = array(
		\T_CONSTANT_ENCAPSED_STRING,
	);

	public static $textString = array(
		\T_ENCAPSED_AND_WHITESPACE,
		\T_CONSTANT_ENCAPSED_STRING,
		\T_INLINE_HTML,
	);

	public static function addSpaces( Tokens $tokens, int $start, int $end ): void {
		// fix white space before index
		$endToken = $tokens[$end - 1];
		if ( $endToken->isWhitespace() ) {
			if ( ! $endToken->isWhitespace( ' \n' ) && ! $tokens[$tokens->getPrevNonWhitespace( $end - 1 )]->isComment() ) {
				$tokens->removeLeadingWhitespace( $end, " \t\0" );
			}
		} else {
			$tokens->insertAt( $end, new Token( array( \T_WHITESPACE, ' ' ) ) );
		}

		// fix white space after index
		$startToken = $tokens[$start + 1];
		if ( $startToken->isWhitespace() ) {
			if ( ! $startToken->isWhitespace( ' \n' ) && ! $tokens[$tokens->getNextNonWhitespace( $start + 1 )]->isComment() ) {
				$tokens->removeTrailingWhitespace( $start, " \t\0" );
			}
		} else {
			$tokens->insertAt( $start + 1, new Token( array( \T_WHITESPACE, ' ' ) ) );
		}
	}

	public static function getBlockContent( int $start, int $end, Tokens $tokens ): array {
		$contents = array();
		for ( $i = ( $start + 1 ); $i < $end; ++$i ) {
			$contents[] = $tokens[ $i ]->getContent();
		}

		return $contents;
	}
}
