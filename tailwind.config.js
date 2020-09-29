module.exports = {
  future: {
    removeDeprecatedGapUtilities: true,
    purgeLayersByDefault: true,
  },
  purge: {
    enabled: true,
    content: [
      './templates/**/*',
      './src/**/*',
      './config/data/**/*',
    ],
  },
  theme: {
    extend: {
      height: {
        '1/3': '33%',
      },
      colors: {
        brand: {
          blue: '#0c35de',
          'blue-dark': '#0c165e',
          purple: '#414a9e',
        },
      },
      screens: {
        'print': {'raw': 'print'},
      },
    },
  },
  variants: {
    fontSize: ['responsive'],
  },
  plugin: [],
};
