<?php

/**
 * LinkWithImage Component
 *
 * @author Alessio Pangos
 */

namespace Components;

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

class LinkWithoutText extends BaseComponent
{

    public function __construct($prefix = '', $classes = 'text-center text-dark font-serif flex flex-col items-center justify-center text-3xl gap-hgap', $getField = true, $forcedContent = false, $forcedPrefix = false)
    {
        parent::__construct($getField);
        $linkArray = Utils::GetLinkAndTarget($prefix, $getField, $forcedPrefix);
        Utils::LinkOpen($linkArray, $classes, '', false, ap_svg('arrow-button', null, 'rem:h-[74px] rem:w-[74px] fill-primary hover:fill-hover -rotate-[120deg]', true));

        Utils::LinkClose($linkArray);
    }
}
