import {
  Box,
  Paper,
} from '@mui/material'
import { ReactNode } from 'react'

interface Props {
  children: ReactNode
}

export default function AuthLayout({
  children,
}: Props) {
  return (
    <Box
      sx={{
        height: '100vh',
        display: 'flex',
        justifyContent: 'center',
        alignItems: 'center',
        bgcolor: '#f5f5f5',
      }}
    >
      <Paper
        elevation={4}
        sx={{
          p: 5,
          width: 420,
        }}
      >
        {children}
      </Paper>
    </Box>
  )
}