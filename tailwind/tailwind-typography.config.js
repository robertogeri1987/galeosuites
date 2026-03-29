module.exports = {
	theme: {
		extend: {
			typography: (theme) => ({
				/**
				 * Tailwind Typography’s default styles are opinionated, and
				 * you may need to override them if you have mockups to
				 * replicate. You can view the default modifiers here:
				 *
				 * https://github.com/tailwindlabs/tailwindcss-typography/blob/master/src/styles.js
				 */
				DEFAULT: {
					css: {
						'--tw-prose-bullets': theme('colors.foreground'),
						'--tw-prose-bold': theme('colors.current'),
						'--tw-prose-links': theme('colors.background'),
						color: theme('colors.foreground'),
					},
				},
			}),
		},
	},
};
