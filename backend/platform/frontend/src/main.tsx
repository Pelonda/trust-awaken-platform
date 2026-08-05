import React from 'react'
import ReactDOM from 'react-dom/client'

import { BrowserRouter } from 'react-router-dom'

import {
  QueryClient,
  QueryClientProvider,
} from '@tanstack/react-query'

import {
  SnackbarProvider,
} from 'notistack'

import {
  CssBaseline,
  ThemeProvider,
} from '@mui/material'

import App from './App'

import theme from './theme/theme'

const queryClient = new QueryClient()

ReactDOM.createRoot(
  document.getElementById('root')!,
).render(

  <React.StrictMode>

    <ThemeProvider theme={theme}>

      <CssBaseline />

      <BrowserRouter>

        <QueryClientProvider
          client={queryClient}
        >

          <SnackbarProvider
            maxSnack={3}
          >

            <App />

          </SnackbarProvider>

        </QueryClientProvider>

      </BrowserRouter>

    </ThemeProvider>

  </React.StrictMode>,
)