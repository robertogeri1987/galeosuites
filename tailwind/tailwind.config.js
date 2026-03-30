// Set flag to include Preflight conditionally based on the build target.
const includePreflight = ('editor' === process.env._TW_TARGET) ? false : true;
const baseFontSize = 16;

const em = px => `${px / 16}em` // do not use baseFontSize here
const rem = px => ({ [px]: `${px / baseFontSize}rem` })

let spacingVals = {};

// Defines a few variables to reflect the design's main grid spacing values
spacingVals['baseFontSize'] = rem(baseFontSize)[baseFontSize];
spacingVals['0'] = rem(0)[0];
spacingVals['gap'] = rem(20)[20];
spacingVals['hhgap'] = rem(5)[5]; // half half gap
spacingVals['hgap'] = rem(10)[10]; // half gap
spacingVals['dgap'] = rem(40)[40]; // double gap
spacingVals['tgap'] = rem(60)[60]; // triple gap
spacingVals['qgap'] = rem(80)[80]; // quad gap

spacingVals['2gap'] = spacingVals['dgap'] // double gap
spacingVals['3gap'] = spacingVals['tgap']; // triple gap
spacingVals['4gap'] = spacingVals['qgap'] // quad gap

spacingVals['vgap'] = rem(108)[108]; // vertical gap
spacingVals['svgap'] = rem(54)[54]; // smallvertical gap

spacingVals['sidebar'] = rem(360)[360]; // quad gap
spacingVals['smallerContent'] = 'var(--smaller-content)';
spacingVals['layoutContent'] = 'var(--layout-content)';

module.exports = {
	presets: [
		// Manage Tailwind Typography's configuration in a separate file.
		require('./tailwind-typography.config.js'),
	],
	content: [
		// Ensure changes to PHP files and `theme.json` trigger a rebuild.
		'./theme/safelist.txt',
		'./theme/**/*.php',
		'./theme/**/**/*.php',
		'./theme/**/**/**/*.php',
		'./theme/**/**/**/**/*.php',
		'./theme/*.php',
		'./theme/theme.json',
		'./theme/*.css',
		'./javascript/**/*.js',
		'./javascript/**/**/*.js',
	],
	theme: {
		screens: {
			xxsd: { max: em(375) }, // d stands for 'down'
			xxs: em(375),
			xsd: { max: em(480) },
			xs: em(480),
			smd: { max: em(768) },
			sm: em(768),
			mdd: { max: em(1024) },
			md: em(1024),
			lgd: { max: em(1280) },
			lg: em(1280),
			xld: { max: em(1440) },
			xl: em(1440),
			'2xld': { max: em(1536) },
			'2xl': em(1536),
			mobile: { max: em(1024) },
			desk: em(1024),
		},
		// Extend the default Tailwind theme.
		extend: {
			fontSize: {
				xs: rem(12)[12],
				sm: rem(14)[14],
				base: rem(baseFontSize)[baseFontSize],
				md: rem(baseFontSize)[baseFontSize],
				'lg': rem(28)[28],
				'xl': rem(32)[32],
				'2xl': rem(42)[42],
				'3xl': rem(55)[55],
				'4xl': rem(75)[75],
				'5xl': rem(95)[95],
			},
			rootFontsize: `${baseFontSize}px`,
			fontFamily: {
				'sans': ['"Raleway"', 'sans-serif'],
				'sans-serif': ['"the-seasons"', 'sans-serif']
			},
			margin: spacingVals,
			padding: spacingVals,
			spacing: spacingVals,
			width: {
				'smallerContent': spacingVals['smallerContent'],
				'layoutContent': spacingVals['layoutContent'],
			},
			maxWidth: {
				'smallerContent': spacingVals['smallerContent'],
				'layoutContent': spacingVals['layoutContent'],
			},
			minWidth: {
				'smallerContent': spacingVals['smallerContent'],
				'layoutContent': spacingVals['layoutContent'],
			}
		},
	},
	corePlugins: {
		// Disable Preflight base styles in CSS targeting the editor.
		preflight: includePreflight,
	},
	plugins: [
		require('tailwindcss-convert-px-to-rem'),
		// Add Tailwind Typography.
		require('@tailwindcss/typography'),

		// Extract colors and widths from `theme.json`.
		require('@_tw/themejson')(require('../theme/theme.json')),

		// Uncomment below to add additional first-party Tailwind plugins.
		// require( '@tailwindcss/aspect-ratio' ),
		// require( '@tailwindcss/forms' ),
		// require( '@tailwindcss/line-clamp' ),
	],
};
