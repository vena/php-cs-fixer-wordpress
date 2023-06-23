<?php

declare( strict_types = 1 );

/**
 * This file contains work sourced under MIT license from:
 * Kuba Werłos - PHP CS Fixer: custom fixers
 * Fabien Potencier - PHP CS Fixer
 * Dariusz Rumiński - PHP CS Fixer.
 */

namespace Tests\Fixer;

use PhpCsFixer\AbstractFixer;
use PhpCsFixer\Fixer\ConfigurableFixerInterface;
use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\Fixer\Whitespace\SingleBlankLineAtEofFixer;
use PhpCsFixer\Fixer\WhitespacesAwareFixerInterface;
use PhpCsFixer\FixerDefinition\FileSpecificCodeSampleInterface;
use PhpCsFixer\FixerDefinition\VersionSpecificCodeSampleInterface;
use PhpCsFixer\Linter\Linter;
use PhpCsFixer\StdinFileInfo;
use PhpCsFixer\Tokenizer\Tokens;
use PhpCsFixer\WhitespacesFixerConfig;
use PHPUnit\Framework\TestCase;
use Tests\AssertRegExpTrait;
use Tests\AssertSameTokensTrait;

/**
 * @internal
 */
abstract class AbstractFixerTestCase extends TestCase {
	use AssertRegExpTrait;
	use AssertSameTokensTrait;

	/** @var FixerInterface|AbstractFixer */
	protected $fixer;

	protected $allowedFixersWithoutDefaultCodeSample;

	protected $allowedRequiredOptions;

	final protected function setUp(): void {
		$reflectionClass = new \ReflectionClass( static::class );

		$className = '\\vena\\WordPress\\PhpCsFixer\\Fixer\\' . mb_substr( $reflectionClass->getShortName(), 0, -4 );

		$fixer = new $className();
		assert( $fixer instanceof FixerInterface, 'Fixer must implement FixerInterface' );

		$this->fixer = $fixer;
		if ( $this->fixer instanceof WhitespacesAwareFixerInterface ) {
			$this->fixer->setWhitespacesConfig( new WhitespacesFixerConfig() );
		}
	}

	final public function testIsRisky(): void {
		if ( $this->fixer->isRisky() ) {
			self::assertValidDescription( $this->fixer->getName(), 'risky description', $this->fixer->getDefinition()->getRiskyDescription() );
		} else {
			static::assertNull( $this->fixer->getDefinition()->getRiskyDescription(), sprintf( '[%s] Fixer is not risky so no description of it expected.', $this->fixer->getName() ) );
		}

		$reflection = new \ReflectionMethod( $this->fixer, 'isRisky' );

		// If fixer is not risky then the method `isRisky` from `AbstractFixer` must be used
		static::assertSame(
			! $this->fixer->isRisky(),
			'PhpCsFixer\AbstractFixer' === $reflection->getDeclaringClass()->getName(),
			'A fixer which is not risky must not override the method `isRisky` from `AbstractFixer`.'
		);
	}

	final public function testFixerDefinitions(): void {
		$fixerName           = $this->fixer->getName();
		$definition          = $this->fixer->getDefinition();
		$fixerIsConfigurable = $this->fixer instanceof ConfigurableFixerInterface;

		self::assertValidDescription( $fixerName, 'summary', $definition->getSummary() );

		$samples = $definition->getCodeSamples();
		static::assertNotEmpty( $samples, sprintf( '[%s] Code samples are required.', $fixerName ) );

		$configSamplesProvided = array();
		$dummyFileInfo         = new StdinFileInfo();

		foreach ( $samples as $sampleCounter => $sample ) {
			static::assertInstanceOf( 'PhpCsFixer\FixerDefinition\CodeSampleInterface', $sample, sprintf( '[%s] Sample #%d', $fixerName, $sampleCounter ) );
			static::assertIsInt( $sampleCounter );

			$code = $sample->getCode();

			static::assertNotEmpty( $code, sprintf( '[%s] Sample #%d', $fixerName, $sampleCounter ) );

			if ( ! ( $this->fixer instanceof SingleBlankLineAtEofFixer ) ) {
				static::assertStringEndsWith( "\n", $code, sprintf( '[%s] Sample #%d must end with linebreak', $fixerName, $sampleCounter ) );
			}

			$config = $sample->getConfiguration();

			if ( null !== $config ) {
				static::assertTrue( $fixerIsConfigurable, sprintf( '[%s] Sample #%d has configuration, but the fixer is not configurable.', $fixerName, $sampleCounter ) );

				$configSamplesProvided[ $sampleCounter ] = $config;
			} elseif ( $fixerIsConfigurable ) {
				if ( ! $sample instanceof VersionSpecificCodeSampleInterface ) {
					static::assertArrayNotHasKey( 'default', $configSamplesProvided, sprintf( '[%s] Multiple non-versioned samples with default configuration.', $fixerName ) );
				}

				$configSamplesProvided['default'] = true;
			}

			if ( $sample instanceof VersionSpecificCodeSampleInterface && ! $sample->isSuitableFor( \PHP_VERSION_ID ) ) {
				continue;
			}

			if ( $fixerIsConfigurable ) {
				// always re-configure as the fixer might have been configured with diff. configuration form previous sample
				$this->fixer->configure( $config ?? array() );
			}

			Tokens::clearCache();
			$tokens = Tokens::fromCode( $code );
			$this->fixer->fix(
				$sample instanceof FileSpecificCodeSampleInterface ? $sample->getSplFileInfo() : $dummyFileInfo,
				$tokens
			);

			static::assertTrue( $tokens->isChanged(), sprintf( '[%s] Sample #%d is not changed during fixing.', $fixerName, $sampleCounter ) );

			$duplicatedCodeSample = array_search(
				$sample,
				array_slice( $samples, 0, $sampleCounter ),
				false
			);

			static::assertFalse(
				$duplicatedCodeSample,
				sprintf( '[%s] Sample #%d duplicates #%d.', $fixerName, $sampleCounter, $duplicatedCodeSample )
			);
		}

		if ( $fixerIsConfigurable ) {
			if ( isset( $configSamplesProvided['default'] ) ) {
				reset( $configSamplesProvided );
				static::assertSame( 'default', key( $configSamplesProvided ), sprintf( '[%s] First sample must be for the default configuration.', $fixerName ) );
			} elseif ( ! isset( $this->allowedFixersWithoutDefaultCodeSample[$fixerName] ) ) {
				static::assertArrayHasKey( $fixerName, $this->allowedRequiredOptions, sprintf( '[%s] Has no sample for default configuration.', $fixerName ) );
			}

			if ( count( $configSamplesProvided ) < 2 ) {
				static::fail( sprintf( '[%s] Configurable fixer only provides a default configuration sample and none for its configuration options.', $fixerName ) );
			}

			$options = $this->fixer->getConfigurationDefinition()->getOptions();

			foreach ( $options as $option ) {
				static::assertMatchesRegularExpression( '/^[a-z_]+[a-z]$/', $option->getName(), sprintf( '[%s] Option %s is not snake_case.', $fixerName, $option->getName() ) );
			}
		}

		static::assertIsInt( $this->fixer->getPriority() );
	}

	final public function testFixersAreFinal(): void {
		$reflection = new \ReflectionClass( $this->fixer );

		static::assertTrue(
			$reflection->isFinal(),
			sprintf( 'Fixer "%s" must be declared "final".', $this->fixer->getName() )
		);
	}

	final public function testFixerConfigurationDefinitions(): void {
		if ( ! $this->fixer instanceof ConfigurableFixerInterface ) {
			$this->expectNotToPerformAssertions(); // not applied to the fixer without configuration

			return;
		}

		$configurationDefinition = $this->fixer->getConfigurationDefinition();

		foreach ( $configurationDefinition->getOptions() as $option ) {
			static::assertInstanceOf( 'PhpCsFixer\FixerConfiguration\FixerOptionInterface', $option );
			static::assertNotEmpty( $option->getDescription() );

			static::assertSame(
				! isset( $this->allowedRequiredOptions[$this->fixer->getName()][$option->getName()] ),
				$option->hasDefault(),
				sprintf(
					$option->hasDefault()
						? 'Option `%s` of fixer `%s` is wrongly listed in `$allowedRequiredOptions` structure, as it is not required. If you just changed that option to not be required anymore, please adjust mentioned structure.'
						: 'Option `%s` of fixer `%s` shall not be required. If you want to introduce new required option please adjust `$allowedRequiredOptions` structure.',
					$option->getName(),
					$this->fixer->getName()
				)
			);

			static::assertStringNotContainsString(
				'DEPRECATED',
				$option->getDescription(),
				'Option description cannot contain word "DEPRECATED"'
			);
		}
	}

	final public function testCodeSampleIsChangedDuringFixing(): void {
		$codeSample = $this->fixer->getDefinition()->getCodeSamples()[0];
		if ( $this->fixer instanceof ConfigurableFixerInterface ) {
			$this->fixer->configure( $codeSample->getConfiguration() ?? array() );
		}

		Tokens::clearCache();
		$tokens = Tokens::fromCode( $codeSample->getCode() );

		$this->fixer->fix( $this->createMock( 'SplFileInfo' ), $tokens );

		self::assertNotSame( $codeSample->getCode(), $tokens->generateCode() );
	}

	final public function testPriority(): void {
		self::assertIsInt( $this->fixer->getPriority() );
	}

	/**
	 * @param array<string, mixed>|null $configuration
	 */
	final protected function doTest( string $expected, ?string $input = null, ?array $configuration = null ): void {
		if ( $this->fixer instanceof ConfigurableFixerInterface ) {
			$this->fixer->configure( $configuration ?? array() );
		}

		if ( $expected === $input ) {
			throw new \InvalidArgumentException( 'Expected must be different to input.' );
		}

		self::assertNull( $this->lintSource( $expected ) );

		Tokens::clearCache();
		$expectedTokens = Tokens::fromCode( $expected );

		if ( null !== $input ) {
			Tokens::clearCache();
			$inputTokens = Tokens::fromCode( $input );

			self::assertTrue( $this->fixer->isCandidate( $inputTokens ), 'Supplied case is not a candidate for this test.' );

			$this->fixer->fix( $this->createMock( 'SplFileInfo' ), $inputTokens );
			$inputTokens->clearEmptyTokens();

			self::assertSame(
				$expected,
				$actual = $inputTokens->generateCode(),
				sprintf(
					"Expected code:\n```\n%s\n```\nGot:\n```\n%s\n```\n",
					$expected,
					$actual
				)
			);

			self::assertSameTokens( $expectedTokens, $inputTokens );
		}

		$this->fixer->fix( $this->createMock( 'SplFileInfo' ), $expectedTokens );

		self::assertSame( $expected, $expectedTokens->generateCode() );

		self::assertFalse( $expectedTokens->isChanged() );
	}

	final protected function lintSource( string $source ): ?string {
		static $linter;

		if ( null === $linter ) {
			$linter = new Linter();
		}

		try {
			$linter->lintSource( $source )->check();
		} catch ( \Exception $exception ) {
			return sprintf( 'Linting "%s" failed with error: %s.', $source, $exception->getMessage() );
		}

		return null;
	}

	private static function assertValidDescription( string $fixerName, string $descriptionType, string $description ): void {
		static::assertMatchesRegularExpression( '/^[A-Z`][^"]+\.$/', $description, sprintf( '[%s] The %s must start with capital letter or a ` and end with dot.', $fixerName, $descriptionType ) );
		static::assertStringNotContainsString( 'phpdocs', $description, sprintf( '[%s] `PHPDoc` must not be in the plural in %s.', $fixerName, $descriptionType ) );
		static::assertCorrectCasing( $description, 'PHPDoc', sprintf( '[%s] `PHPDoc` must be in correct casing in %s.', $fixerName, $descriptionType ) );
		static::assertCorrectCasing( $description, 'PHPUnit', sprintf( '[%s] `PHPUnit` must be in correct casing in %s.', $fixerName, $descriptionType ) );
		static::assertFalse( mb_strpos( $descriptionType, '``' ), sprintf( '[%s] The %s must no contain sequential backticks.', $fixerName, $descriptionType ) );
	}

	private static function assertCorrectCasing( string $needle, string $haystack, string $message ): void {
		static::assertSame( mb_substr_count( mb_strtolower( $haystack ), mb_strtolower( $needle ) ), mb_substr_count( $haystack, $needle ), $message );
	}
}
