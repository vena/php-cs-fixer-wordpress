<?php

namespace vena\WordPress\PhpCsFixer;

use PhpCsFixer\RuleSet\AbstractRuleSetDescription;

final class WordPressRuleSet extends AbstractRuleSetDescription {
	public function getDescription(): string {
		return 'Rules that follow the official `Wordpress Coding Standards <https://developer.wordpress.org/coding-standards/wordpress-coding-standards/php/>`_.';
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
			// Built-ins
			'@PSR2' => true,
			'@PhpCsFixer' => true,
			'align_multiline_comment' => array( 'comment_type' => 'phpdocs_like' ),
			'array_syntax' => array( 'syntax' => 'long' ),
			'binary_operator_spaces' => true,
			'blank_line_after_opening_tag' => false,
			'braces' => array(
				'position_after_functions_and_oop_constructs' => 'same',
			),
			'cast_spaces' => true,
			'class_attributes_separation' => array(
				'elements' => array(
					'const' => 'one',
					'method' => 'one',
					'property' => 'only_if_meta',
				),
			),
			'class_definition' => array( 'single_line' => true ),
			'class_keyword_remove' => true,
			'concat_space' => array( 'spacing' => 'one' ),
			'control_structure_continuation_position' => true,
			'dir_constant' => true,
			'fully_qualified_strict_types' => true,
			'global_namespace_import' => true,
			'include' => true,
			'list_syntax' => array( 'syntax' => 'long' ),
			'lowercase_cast' => true,
			'lowercase_static_reference' => true,
			'magic_constant_casing' => true,
			'magic_method_casing' => true,
			'method_chaining_indentation' => true,
			'native_constant_invocation' => true,
			'native_function_casing' => true,
			'native_function_type_declaration_casing' => true,
			'new_with_braces' => true,
			'no_alternative_syntax' => array( 'fix_non_monolithic_code' => false ),
			'no_blank_lines_after_class_opening' => false,
			'no_blank_lines_after_phpdoc' => true,
			'no_empty_comment' => true,
			'no_extra_blank_lines' => array(
				'tokens' => array(
					'continue',
					'extra',
					'parenthesis_brace_block',
					'square_brace_block',
					'throw',
					'use',
				),
			),
			'no_spaces_around_offset' => array( 'positions' => array( 'outside' ) ),
			'no_spaces_inside_parenthesis' => false,
			'not_operator_with_space' => true,
			// 'not_operator_with_successor_space' => true,
			'phpdoc_tag_casing' => true,
			'phpdoc_types_order' => array(
				'null_adjustment' => 'always_last',
				'sort_algorithm' => 'none',
			),
			'single_line_throw' => true,
			'strict_param' => true,
			'trim_array_spaces' => true,
			// WPCS 3.0 proposal, yoda style is optional
			'yoda_style' => array(
				'always_move_variable' => false,
				'equal' => false,
				'identical' => false,
			),

			// Custom
			'Vena/wp_parentheses_spaces' => true,
			'Vena/wp_array_index_spaces' => true,
			'Vena/wp_multiline_assoc_arrays' => true,
		);
	}

	public function isRisky(): bool {
		return true;
	}
}
