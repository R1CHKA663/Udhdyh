module.exports = {
	env: {
		browser: true,
		es2021: true,
		node: true,
	},
	extends: ['eslint:recommended', 'plugin:vue/essential'],
	parserOptions: {
		ecmaVersion: 12,
		sourceType: 'module',
	},
	plugins: ['vue'],
	rules: {
		'no-console': process.env.NODE_ENV === 'production' ? 'warn' : 'off',
		'no-debugger': process.env.NODE_ENV === 'production' ? 'warn' : 'off',
		'no-mixed-spaces-and-tabs': 0,
		'vue/no-multiple-template-root': 0,
		'vue/multi-word-component-names': 0,
		'vue/no-reserved-component-names': 0,
		'no-unused-vars': 0,
		'no-empty': 0,
	},
}
