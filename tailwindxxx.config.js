/** @type {import('tailwindcss').Config} */
module.exports = {
    content: ["./resources/views/**/*.blade.php", "./public/**/*.html"],
    theme: {
        extend: {},
        fontFamily: {
            sans: ["Inter"],
        },
        // fontSize: {
        //     "display-xl": [
        //         "3.75rem",
        //         {
        //             lineHeight: "4.5rem",
        //         },
        //     ],
        //     "display-lg": [
        //         "3rem",
        //         {
        //             lineHeight: "3.75rem",
        //         },
        //     ],
        //     "display-md": [
        //         "2.25rem",
        //         {
        //             lineHeight: "2.75rem",
        //         },
        //     ],
        //     "display-sm": [
        //         "1.875rem",
        //         {
        //             lineHeight: "2.375rem",
        //         },
        //     ],
        //     "display-xs": [
        //         "1.5rem",
        //         {
        //             lineHeight: "2rem",
        //         },
        //     ],
        //     xl: [
        //         "1.25rem",
        //         {
        //             lineHeight: "1.875rem",
        //         },
        //     ],
        //     lg: [
        //         "1.125rem",
        //         {
        //             lineHeight: "1.75rem",
        //         },
        //     ],
        //     md: [
        //         "1rem",
        //         {
        //             lineHeight: "1.5rem",
        //         },
        //     ],
        //     sm: [
        //         "0.875rem",
        //         {
        //             lineHeight: "1.25rem",
        //         },
        //     ],
        //     xs: [
        //         "0.75rem",
        //         {
        //             lineHeight: "1.25rem",
        //             //   letterSpacing: '-0.01em',
        //             //   fontWeight: '500',
        //         },
        //     ],
        // },
        colors: {
            neutral: {
                0: "#FFF",
                50: "#F0F1F3",
                100: "#E0E2E7",
                150: "#D1D4DA",
                200: "#C2C6CE",
                300: "#A3A9B6",
                400: "#858D9D",
                500: "#667085",
                600: "#525A6A",
                700: "#3D4350",
                800: "#292D35",
                850: "#1F2228",
                900: "#14161B",
                950: "#0A0B0D",
                1000: "#000",
            },
            brand: {
                25: "#F5FAFF",
                50: "#EFF8FF",
                100: "#D2E9FF",
                200: "#B2DDFF",
                300: "#85CAFF",
                400: "#53B1FD",
                500: "#2E90FA",
                600: "#1470EF",
                700: "#175CD3",
                800: "#1849A9",
                900: "#1A4185",
                950: "#112A56",
            },
            success: {
                25: "#F7FEF9",
                50: "#ECFEF3",
                100: "#DCFAE6",
                200: "#DCFAE6",
                300: "#75E1A7",
                400: "#46CD89",
                500: "#19B26B",
                600: "#079455",
                700: "#047647",
                800: "#065D3A",
                900: "#084D31",
                950: "#053321",
            },
            warning: {
                25: "#FFFCF5",
                50: "#FFFAEA",
                100: "#FEF1C6",
                200: "#FEDF88",
                300: "#FFC84B",
                400: "#FDB022",
                500: "#F79008",
                600: "#DC6903",
                700: "#B54707",
                800: "#93370E",
                900: "#7A2E0D",
                950: "#4E1D09",
            },
            error: {
                25: "#FFFBFA",
                50: "#FEF3F2",
                100: "#FEE4E2",
                200: "#FECDCA",
                300: "#FDA29B",
                400: "#F97066",
                500: "#F14437",
                600: "#D73328",
                700: "#B42419",
                800: "#912019",
                900: "#7B271A",
                950: "#55160C",
            },
        },
    },
    plugins: [],
};
