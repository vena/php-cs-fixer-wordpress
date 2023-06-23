<?php

namespace vena\WordPress\PhpCsFixer;

use PhpCsFixer\RuleSet\AbstractRuleSetDescription;

final class WordPressRuleSet extends AbstractRuleSetDescription {
	public function getDescription(): string {
		return 'Rules that (mostly) follow the official `Wordpress Coding Standards <https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/>`_.';
	}

	public function getCustomFixers(): array {
		return array(
			new \vena\WordPress\PhpCsFixer\Fixer\WordPressParenthesesSpacesFixer(),
			new \vena\WordPress\PhpCsFixer\Fixer\WordPressArrayIndexSpacesFixer(),
			new \vena\WordPress\PhpCsFixer\Fixer\WordPressMultilineAssocArrayFixer(),
		);
	}

	public function getRules(): array {
		return array(
			// Each line of multi-line DocComments must have an asterisk [PSR-5] and must be aligned with the first one.
			'align_multiline_comment' => array( 'comment_type' => 'all_multiline' ),
			// Each element of an array must be indented exactly once.
			'array_indentation' => true,
			// PHP arrays should be declared using the configured syntax.
			'array_syntax' => array( 'syntax' => 'long' ),
			// Use the null coalescing assignment operator `??=` where possible.
			'assign_null_coalescing_to_coalesce_equal' => true,
			// Converts backtick operators to `shell_exec` calls.
			'backtick_to_shell_exec' => true,
			// Binary operators should be surrounded by space as configured.
			'binary_operator_spaces' => array( 'default' => 'align_single_space_minimal' ),
			// There MUST be one blank line after the namespace declaration.
			'blank_line_after_namespace' => true,
			// Ensure there is no code on the same line as the PHP open tag and it is followed by a blank line.
			'blank_line_after_opening_tag' => true,
			// An empty line feed must precede any configured statement.
			'blank_line_before_statement' => array( 'statements' => array( 'break', 'continue', 'declare', 'exit', 'return', 'throw', 'try' ) ),
			// Putting blank lines between `use` statement groups.
			'blank_line_between_import_groups' => true,
			// A single space or none should be between cast and variable.
			'cast_spaces' => array( 'space' => 'single' ),
			// Class, trait and interface elements must be separated with one or none blank line.
			'class_attributes_separation' => true,
			// Whitespace around the keywords of a class, trait, enum or interfaces definition should be one space.
			'class_definition' => array( 'single_line' => true ),
			// When referencing an internal class it must be written using the correct casing.
			'class_reference_name_casing' => true,
			// Namespace must not contain spacing, comments or PHPDoc.
			'clean_namespace' => true,
			// Using `isset($var) &&` multiple times should be done in one call.
			'combine_consecutive_issets' => true,
			// Calling `unset` on multiple items should be done in one call.
			'combine_consecutive_unsets' => true,
			// Remove extra spaces in a nullable typehint.
			'compact_nullable_typehint' => true,
			// Concatenation should be spaced according to configuration.
			'concat_space' => array( 'spacing' => 'one' ),
			// The PHP constants `true`, `false`, and `null` MUST be written using the correct casing.
			'constant_case' => true,
			// The body of each control structure MUST be enclosed within braces.
			'control_structure_braces' => true,
			// Control structure continuation keyword must be on the configured line.
			'control_structure_continuation_position' => true,
			// Curly braces must be placed as configured.
			'curly_braces_position' => array(
				'classes_opening_brace'   => 'same_line',
				'functions_opening_brace' => 'same_line',
			),
			// Equal sign in declare statement should be surrounded by spaces or not following configuration.
			'declare_equal_normalize' => array( 'space' => 'single' ),
			// There must not be spaces around `declare` statement parentheses.
			'declare_parentheses' => true,
			// Replaces `dirname(__FILE__)` expression with equivalent `__DIR__` constant.
			'dir_constant' => true,
			// Replaces short-echo `<?=` with long format `<?php echo`/`<?php print` syntax, or vice-versa.
			'echo_tag_syntax' => true,
			// The keyword `elseif` should be used instead of `else if` so that all control keywords look like single words.
			'elseif' => true,
			// Empty loop-body must be in configured style.
			'empty_loop_body' => array( 'style' => 'braces' ),
			// Empty loop-condition must be in configured style.
			'empty_loop_condition' => true,
			// PHP code MUST use only UTF-8 without BOM (remove BOM).
			'encoding' => true,
			// Escape implicit backslashes in strings and heredocs to ease the understanding of which are special chars interpreted by PHP and which not.
			'escape_implicit_backslashes' => true,
			// Add curly braces to indirect variables to make them clear to understand. Requires PHP >= 7.0.
			'explicit_indirect_variable' => true,
			// Converts implicit variables into explicit ones in double-quoted strings or heredoc syntax.
			'explicit_string_variable' => true,
			// PHP code must use the long `<?php` tags or short-echo `<?=` tags and not other tag variations.
			'full_opening_tag' => true,
			// Transforms imported FQCN parameters and return types in function arguments to short version.
			'fully_qualified_strict_types' => true,
			// Spaces should be properly placed in a function declaration.
			'function_declaration' => true,
			// Ensure single space between function's argument and its typehint.
			'function_typehint_space' => true,
			// Renames PHPDoc tags.
			'general_phpdoc_tag_rename' => array( 'replacements' => array( 'inheritDocs' => 'inheritDoc' ) ),
			// Imports or fully qualifies global classes/functions/constants.
			'global_namespace_import' => array(
				'import_classes'   => false,
				'import_constants' => false,
				'import_functions' => false,
			),
			// Convert `heredoc` to `nowdoc` where possible.
			'heredoc_to_nowdoc' => true,
			// Function `implode` must be called with 2 arguments in the documented order.
			'implode_call' => true,
			// Include/Require and file path should be divided with a single space. File path should not be placed under brackets.
			'include' => true,
			// Pre- or post-increment and decrement operators should be used if possible.
			'increment_style' => true,
			// Code MUST use configured indentation type.
			'indentation_type' => true,
			// Integer literals must be in correct case.
			'integer_literal_case' => true,
			// Lambda must not import variables it doesn't use.
			'lambda_not_used_import' => true,
			// All PHP files must use same line ending.
			'line_ending' => true,
			// Ensure there is no code on the same line as the PHP open tag.
			'linebreak_after_opening_tag' => true,
			// List (`array` destructuring) assignment should be declared using the configured syntax. Requires PHP >= 7.1.
			'list_syntax' => array( 'syntax' => 'long' ),
			// Cast should be written in lower case.
			'lowercase_cast' => true,
			// PHP keywords MUST be in lower case.
			'lowercase_keywords' => true,
			// Class static references `self`, `static` and `parent` MUST be in lower case.
			'lowercase_static_reference' => true,
			// Magic constants should be referred to using the correct casing.
			'magic_constant_casing' => true,
			// Magic method definitions and calls must be using the correct casing.
			'magic_method_casing' => true,
			// Replace non multibyte-safe functions with corresponding mb function.
			'mb_str_functions' => true,
			// In method arguments and method call, there MUST NOT be a space before each comma and there MUST be one space after each comma. Argument lists MAY be split across multiple lines, where each subsequent line is indented once. When doing so, the first item in the list MUST be on the next line, and there MUST be only one argument per line.
			'method_argument_space' => array( 'on_multiline' => 'ensure_fully_multiline' ),
			// Method chaining MUST be properly indented. Method chaining with different levels of indentation is not supported.
			'method_chaining_indentation' => true,
			// DocBlocks must start with two asterisks, multiline comments must start with a single asterisk, after the opening slash. Both must end with a single asterisk before the closing slash.
			'multiline_comment_opening_closing' => true,
			// Forbid multi-line whitespace before the closing semicolon or move the semicolon to the new line for chained calls.
			'multiline_whitespace_before_semicolons' => true,
			// Add leading `\` before constant invocation of internal constant to speed up resolving. Constant name match is case-sensitive, except for `null`, `false` and `true`.
			'native_constant_invocation' => true,
			// Function defined by PHP should be called using the correct casing.
			'native_function_casing' => true,
			// Add leading `\` before function invocation to speed up resolving.
			'native_function_invocation' => array( 'include' => array( 'NFI_includes' ) ),
			// Native type hints for functions should use the correct case.
			'native_function_type_declaration_casing' => true,
			// All instances created with `new` keyword must (not) be followed by braces.
			'new_with_braces' => true,
			// Master language constructs shall be used instead of aliases.
			'no_alias_language_construct_call' => true,
			// Replace control structure alternative syntax to use braces.
			'no_alternative_syntax' => array( 'fix_non_monolithic_code' => false ),
			// There should not be a binary flag before strings.
			'no_binary_string' => true,
			// There should be no empty lines after class opening brace.
			'no_blank_lines_after_class_opening' => true,
			// There should not be blank lines between docblock and the documented element.
			'no_blank_lines_after_phpdoc' => true,
			// There must be a comment when fall-through is intentional in a non-empty case body.
			'no_break_comment' => true,
			// The closing `? >` tag MUST be omitted from files containing only PHP.
			'no_closing_tag' => true,
			// There should not be any empty comments.
			'no_empty_comment' => true,
			// There should not be empty PHPDoc blocks.
			'no_empty_phpdoc' => true,
			// Remove useless (semicolon) statements.
			'no_empty_statement' => true,
			// Removes extra blank lines and/or blank lines following configuration.
			'no_extra_blank_lines' => array( 'tokens' => array( 'break', 'continue', 'extra', 'return', 'throw' ) ),
			// Remove leading slashes in `use` clauses.
			'no_leading_import_slash' => true,
			// The namespace declaration line shouldn't contain leading whitespace.
			'no_leading_namespace_whitespace' => true,
			// Either language construct `print` or `echo` should be used.
			'no_mixed_echo_print' => true,
			// Operator `=>` should not be surrounded by multi-line whitespaces.
			'no_multiline_whitespace_around_double_arrow' => true,
			// There must not be more than one statement per line.
			'no_multiple_statements_per_line' => true,
			// Properties MUST not be explicitly initialized with `null` except when they have a type declaration (PHP 7.4).
			'no_null_property_initialization' => true,
			// Short cast `bool` using double exclamation mark should not be used.
			'no_short_bool_cast' => true,
			// Single-line whitespace before closing semicolon are prohibited.
			'no_singleline_whitespace_before_semicolons' => true,
			// There must be no space around double colons (also called Scope Resolution Operator or Paamayim Nekudotayim).
			'no_space_around_double_colon' => true,
			// When making a method or function call, there MUST NOT be a space between the method or function name and the opening parenthesis.
			'no_spaces_after_function_name' => true,
			// There MUST NOT be spaces around offset braces.
			'no_spaces_around_offset' => array( 'positions' => array( 'outside' ) ),
			// Replaces superfluous `elseif` with `if`.
			'no_superfluous_elseif' => true,
			// Removes `@param`, `@return` and `@var` tags that don't provide any useful information.
			'no_superfluous_phpdoc_tags' => array(
				'allow_mixed'         => true,
				'allow_unused_params' => true,
			),
			// If a list of values separated by a comma is contained on a single line, then the last item MUST NOT have a trailing comma.
			'no_trailing_comma_in_singleline' => true,
			// Remove trailing whitespace at the end of non-blank lines.
			'no_trailing_whitespace' => true,
			// There MUST be no trailing spaces inside comment or PHPDoc.
			'no_trailing_whitespace_in_comment' => true,
			// Removes unneeded parentheses around control statements.
			'no_unneeded_control_parentheses' => true,
			// Removes unneeded curly braces that are superfluous and aren't part of a control structure's body.
			'no_unneeded_curly_braces' => array( 'namespaces' => true ),
			// Imports should not be aliased as the same name.
			'no_unneeded_import_alias' => true,
			// Variables must be set `null` instead of using `(unset)` casting.
			'no_unset_cast' => true,
			// Unused `use` statements must be removed.
			'no_unused_imports' => true,
			// There should not be useless concat operations.
			'no_useless_concat_operator' => true,
			// There should not be useless `else` cases.
			'no_useless_else' => true,
			// There should not be useless `null-safe-operators` `?->` used.
			'no_useless_nullsafe_operator' => true,
			// There should not be an empty `return` statement at the end of a function.
			'no_useless_return' => true,
			// In array declaration, there MUST NOT be a whitespace before each comma.
			'no_whitespace_before_comma_in_array' => true,
			// Remove trailing whitespace at the end of blank lines.
			'no_whitespace_in_blank_line' => true,
			// Remove Zero-width space (ZWSP), Non-breaking space (NBSP) and other invisible unicode symbols.
			'non_printable_character' => true,
			// Array index should always be written by using square braces.
			'normalize_index_brace' => true,
			// Logical NOT operators (`!`) should have leading and trailing whitespaces.
			'not_operator_with_space' => true,
			// Logical NOT operators (`!`) should have one trailing whitespace.
			'not_operator_with_successor_space' => true,
			// There should not be space before or after object operators `->` and `?->`.
			'object_operator_without_whitespace' => true,
			// Operators - when multiline - must always be at the beginning or at the end of the line.
			'operator_linebreak' => true,
			// Orders the elements of classes/interfaces/traits/enums.
			'ordered_class_elements' => true,
			// Ordering `use` statements.
			'ordered_imports' => array(
				'imports_order'  => array( 'class', 'function', 'const' ),
				'sort_algorithm' => 'alpha',
			),
			// PHPUnit annotations should be a FQCNs including a root namespace.
			'php_unit_fqcn_annotation' => true,
			// All PHPUnit test classes should be marked as internal.
			'php_unit_internal_class' => true,
			// Enforce camel (or snake) case for PHPUnit test methods, following configuration.
			'php_unit_method_casing' => true,
			// Adds a default `@coversNothing` annotation to PHPUnit test classes that have no `@covers*` annotation.
			'php_unit_test_class_requires_covers' => true,
			// PHPDoc should contain `@param` for all params.
			'phpdoc_add_missing_param_annotation' => true,
			// All items of the given phpdoc tags must be either left-aligned or (by default) aligned vertically.
			'phpdoc_align' => true,
			// PHPDoc annotation descriptions should not be a sentence.
			'phpdoc_annotation_without_dot' => true,
			// Docblocks should have the same indentation as the documented subject.
			'phpdoc_indent' => true,
			// Fixes PHPDoc inline tags.
			'phpdoc_inline_tag_normalizer' => true,
			// `@access` annotations should be omitted from PHPDoc.
			'phpdoc_no_access' => true,
			// No alias PHPDoc tags should be used.
			'phpdoc_no_alias_tag' => true,
			// `@return void` and `@return null` annotations should be omitted from PHPDoc.
			'phpdoc_no_empty_return' => true,
			// `@package` and `@subpackage` annotations should be omitted from PHPDoc.
			'phpdoc_no_package' => true,
			// Classy that does not inherit must not have `@inheritdoc` tags.
			'phpdoc_no_useless_inheritdoc' => true,
			// Annotations in PHPDoc should be ordered in defined sequence.
			'phpdoc_order' => true,
			// Order phpdoc tags by value.
			'phpdoc_order_by_value' => true,
			// The type of `@return` annotations of methods returning a reference to itself must the configured one.
			'phpdoc_return_self_reference' => true,
			// Scalar types should always be written in the same form. `int` not `integer`, `bool` not `boolean`, `float` not `real` or `double`.
			'phpdoc_scalar' => true,
			// Annotations in PHPDoc should be grouped together so that annotations of the same type immediately follow each other. Annotations of a different type are separated by a single blank line.
			'phpdoc_separation' => true,
			// Single line `@var` PHPDoc should have proper spacing.
			'phpdoc_single_line_var_spacing' => true,
			// PHPDoc summary should end in either a full stop, exclamation mark, or question mark.
			'phpdoc_summary' => true,
			// Fixes casing of PHPDoc tags.
			'phpdoc_tag_casing' => true,
			// Forces PHPDoc tags to be either regular annotations or inline.
			'phpdoc_tag_type' => array( 'tags' => array( 'inheritDoc' => 'inline' ) ),
			// Docblocks should only be used on structural elements.
			'phpdoc_to_comment' => true,
			// PHPDoc should start and end with content, excluding the very first and last line of the docblocks.
			'phpdoc_trim' => true,
			// Removes extra blank lines after summary and after description in PHPDoc.
			'phpdoc_trim_consecutive_blank_line_separation' => true,
			// The correct case must be used for standard PHP types in PHPDoc.
			'phpdoc_types' => true,
			// Sorts PHPDoc types.
			'phpdoc_types_order' => array(
				'null_adjustment' => 'always_last',
				'sort_algorithm'  => 'none',
			),
			// `@var` and `@type` annotations must have type and name in the correct order.
			'phpdoc_var_annotation_correct_order' => true,
			// `@var` and `@type` annotations of classy properties should not contain the name.
			'phpdoc_var_without_name' => true,
			// Converts `protected` variables and methods to `private` where possible.
			'protected_to_private' => true,
			// Local, dynamic and directly referenced variables should not be assigned and directly returned by a function or method.
			'return_assignment' => true,
			// Adjust spacing around colon in return type declarations and backed enum types.
			'return_type_declaration' => array( 'space_before' => 'none' ),
			// Instructions must be terminated with a semicolon.
			'semicolon_after_instruction' => true,
			// Cast `(boolean)` and `(integer)` should be written as `(bool)` and `(int)`, `(double)` and `(real)` as `(float)`, `(binary)` as `(string)`.
			'short_scalar_cast' => true,
			// Converts explicit variables in double-quoted strings and heredoc syntax from simple to complex format (`${` to `{$`).
			'simple_to_complex_string_variable' => true,
			// A return statement wishing to return `void` should not return `null`.
			'simplified_null_return' => true,
			// A PHP file without end tag must always end with a single empty line feed.
			'single_blank_line_at_eof' => true,
			// There should be exactly one blank line before a namespace declaration.
			'single_blank_line_before_namespace' => true,
			// There MUST NOT be more than one property or constant declared per statement.
			'single_class_element_per_statement' => array( 'elements' => array( 'const', 'property' ) ),
			// There MUST be one use keyword per declaration.
			'single_import_per_statement' => array( 'group_to_single_imports' => true ),
			// Each namespace use MUST go on its own line and there MUST be one blank line after the use statements block.
			'single_line_after_imports' => true,
			// Single-line comments must have proper spacing.
			'single_line_comment_spacing' => true,
			// Single-line comments and multi-line comments with only one line of actual content should use the `//` syntax.
			'single_line_comment_style' => true,
			// Convert double quotes to single quotes for simple strings.
			'single_quote' => true,
			// Ensures a single space after language constructs.
			'single_space_around_construct' => true,
			// Each trait `use` must be done as single statement.
			'single_trait_insert_per_statement' => true,
			// Fix whitespace after a semicolon.
			'space_after_semicolon' => true,
			// Increment and decrement operators should be used if possible.
			'standardize_increment' => true,
			// Replace all `<>` with `!=`.
			'standardize_not_equals' => true,
			// Each statement must be indented.
			'statement_indentation' => true,
			// A case should be followed by a colon and not a semicolon.
			'switch_case_semicolon_to_colon' => true,
			// Removes extra spaces between colon and case value.
			'switch_case_space' => true,
			// Switch case must not be ended with `continue` but with `break`.
			'switch_continue_to_break' => true,
			// Standardize spaces around ternary operator.
			'ternary_operator_spaces' => true,
			// Multi-line arrays, arguments list, parameters list and `match` expressions must have a trailing comma.
			'trailing_comma_in_multiline' => true,
			// A single space or none should be around union type and intersection type operators.
			'types_spaces' => array( 'space' => 'single' ),
			// Unary operators should be placed adjacent to their operands.
			'unary_operator_spaces' => true,
			// Visibility MUST be declared on all properties and methods; `abstract` and `final` MUST be declared before the visibility; `static` MUST be declared after the visibility.
			'visibility_required' => true,
			// In array declaration, there MUST be a whitespace after each comma.
			'whitespace_after_comma_in_array' => array( 'ensure_single_space' => true ),

			// Custom
			'Vena/wp_parentheses_spaces'     => true,
			'Vena/wp_array_index_spaces'     => true,
			'Vena/wp_multiline_assoc_arrays' => true,
		);
	}

	public function isRisky(): bool {
		return false;
	}
}
