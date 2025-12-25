// tailwind.config.js
import { heroui } from "@heroui/theme";

/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./src/**/*.{js,ts,jsx,tsx,mdx}",
    "./src/app/**/*.{js,ts,jsx,tsx,mdx}",
    "./src/pages/**/*.{js,ts,jsx,tsx,mdx}",
    "./src/components/**/*.{js,ts,jsx,tsx,mdx}",
    "./node_modules/@heroui/theme/dist/**/*.{js,ts,jsx,tsx}",
  ],
  theme: {
    extend: {},
  },
  darkMode: "class",
  plugins: [
    heroui({
      themes: {
        light: {
          colors: {
            primary: {
              50: "#f7fbf4",
              100: "#e7f5de",
              200: "#cfebbd",
              300: "#afde92",
              400: "#87ce5b",
              500: "#60BE25", // Primary Base
              600: "#51a11f",
              700: "#438519",
              800: "#305f12",
              900: "#182f09",
              950: "#0e1c05",
              DEFAULT: "#60BE25",
              foreground: "#ffffff",
            },
            secondary: {
              50: "#f2f9fd",
              100: "#d8edfb",
              200: "#b2dcf8",
              300: "#7fc6f4",
              400: "#3fa9ef",
              500: "#008DEA", // Secondary Base
              600: "#0077c6",
              700: "#0062a3",
              800: "#004675",
              900: "#00233a",
              950: "#001523",
              DEFAULT: "#008DEA",
              foreground: "#ffffff",
            },
          },
        },
        dark: {
          colors: {
            background: "#0a0a0a",
            foreground: "#ededed",
          },
        },
      },
    }),
  ],
};