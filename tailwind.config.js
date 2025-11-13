module.exports = {
  theme: {
    extend: {
      fontFamily: {
        'title': ['Momo Trust Display', 'sans-serif'],
        'sans': ['Open Sans', 'sans-serif'],
      },
    },
  },
  content: [
    './resources/views/**/*.blade.php',
    './resources/js/**/*.js',
    './resources/js/**/*.vue',
    './resources/css/**/*.css',
    './app/Http/Livewire/**/*.php',
    './resources/**/*.md',
  ],
  plugins: [],
}
