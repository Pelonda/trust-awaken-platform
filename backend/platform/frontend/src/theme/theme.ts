import { createTheme } from '@mui/material/styles'

const theme = createTheme({

  palette: {

    mode: 'light',

    primary: {
      main: '#2563eb',
    },

    secondary: {
      main: '#0f172a',
    },

    background: {
      default: '#f5f7fb',
      paper: '#ffffff',
    },

  },

  shape: {
    borderRadius: 12,
  },

  typography: {

    fontFamily:
      'Inter, Roboto, Helvetica, Arial, sans-serif',

    h4: {
      fontWeight: 700,
    },

    h5: {
      fontWeight: 700,
    },

    button: {
      textTransform: 'none',
      fontWeight: 600,
    },

  },

  components: {

    MuiPaper: {
      styleOverrides: {
        root: {
          borderRadius: 14,
        },
      },
    },

    MuiButton: {
      styleOverrides: {
        root: {
          borderRadius: 10,
          paddingInline: 18,
        },
      },
    },

    MuiTextField: {
      defaultProps: {
        size: 'small',
        fullWidth: true,
      },
    },

  },

})

export default theme