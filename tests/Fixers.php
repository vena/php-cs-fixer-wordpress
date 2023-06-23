<?php

declare( strict_types = 1 );

/*
 * This file is part of PHP CS Fixer: custom fixers.
 *
 * (c) 2018 Kuba Werłos
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace Tests;

use PhpCsFixer\Fixer\FixerInterface;

/**
 * @implements \IteratorAggregate<FixerInterface>
 */
final class Fixers implements \IteratorAggregate {
	/**
	 * @return \Generator<FixerInterface>
	 */
	public function getIterator(): \Generator {
		$classNames = array();
		foreach ( new \DirectoryIterator( __DIR__ . '/../src/Fixer' ) as $fileInfo ) {
			$fileName = $fileInfo->getBasename( '.php' );
			if ( in_array( $fileName, array( '.', '..', 'AbstractFixer', 'AbstractTypesFixer', 'DeprecatingFixerInterface' ), true ) ) {
				continue;
			}
			$file = new \SplFileObject( $fileInfo->getPathname(), 'r' );
			while ( ! $file->eof() ) {
				$chunk = $file->fread( 1024 );
				if ( preg_match( '/namespace ([^;]+);/', $chunk, $namespace ) ) {
					$classNames[] = $namespace[1] . '\\' . $fileName;
				}
			}
		}

		sort( $classNames );

		foreach ( $classNames as $className ) {
			$fixer = new $className();
			assert( $fixer instanceof FixerInterface );

			yield $fixer;
		}
	}
}
