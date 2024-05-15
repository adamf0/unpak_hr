/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./src/**/*.{js,jsx,ts,tsx}",],
  important: true,
  theme: {
    extend: {
      screens: {
        '3xs': {'min':'256px','max':'383px'}, //256 320
        '2xs': {'min':'384px','max':'511px'},
        'xs': {'min':'512px','max':'639px'},
        'sm': {'min':'640px','max':'767px'},
        'md': {'min':'768px','max':'1023px'},
        'lg': {'min':'1024px','max':'1279px'},
        'xl': {'min':'1280px','max':'1535px'},
        '2xl': {'min':'1536px'},
      },
      colors:{
        "primary" : "#6C53B4",
        "primary-dark" : "#49318F",
        "strong-cyan" : "#06B6D4",
      },
      // transformOrigin: {
      //   "0": "0%",
      // },
      // zIndex: {
      //   "-1": "-1",
      // },
    }
  },
  plugins: [],
}

