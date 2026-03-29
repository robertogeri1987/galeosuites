<?php

/**
 * LinkWithImage Component
 *
 * @author Alessio Pangos
 */

namespace Components;

// If this file is called directly, abort.
if (! defined('WPINC')) {
	die;
}

class LinkWithImage extends BaseComponent
{
	public function __construct(
		$prefix = '',
		$classes = 'text-dark font-serif text-3xl inline-flex justify-center',
		$getField = true,
		$forcedContent = false,
		$forcedPrefix = false
	) {
		parent::__construct($getField);

		$linkArray = Utils::GetLinkAndTarget($prefix, $getField, $forcedPrefix);

		$title = esc_html($linkArray['title'] ?? '');

		if (! $forcedContent) {
			$forcedContent = ap_svg(
				'arrow-long',
				null,
				'rem:h-[74px] rem:w-[74px] fill-primary hover:fill-hover stroke-current',
				true
			);
		}

		// Wrapper interno flex con gap
		$inner = '<span class="mx-auto">'
			.    '<span class="whitespace-nowrap mr-2">' . $title . '</span>'
			.    '<span aria-hidden="true">' . $forcedContent . '</span>'
			. '</span>';

		// Passo il contenuto a LinkOpen
		Utils::LinkOpen($linkArray, $classes, $inner, false, false);

		Utils::LinkClose($linkArray);
	}
}