import {
  Alert,
  Box,
  Button,
  Card,
  CardContent,
  Stack,
  TextField,
  Typography,
} from '@mui/material'

import { useState } from 'react'
import { useNavigate } from 'react-router-dom'

import { useLogin } from '../features/auth'
import { useAuth } from '../context/AuthContext'

export default function LoginPage() {

  const navigate = useNavigate()

  const { login: authenticate } = useAuth()

  const mutation = useLogin()

  const [email, setEmail] = useState('')
  const [password, setPassword] = useState('')

  const [error, setError] = useState('')

  function submit() {

    setError('')

    mutation.mutate(
      {
        email,
        password,
      },
      {
        onSuccess: (response: any) => {

          authenticate(
            response.user,
            response.token,
          )

          navigate('/dashboard')

        },

        onError: () => {

          setError(
            'Invalid email or password.'
          )

        },

      }
    )

  }

  return (

    <Box
      sx={{
        display: 'flex',
        justifyContent: 'center',
        alignItems: 'center',
        minHeight: '100vh',
        bgcolor: '#f4f7fb',
      }}
    >

      <Card
        elevation={0}
        sx={{
          width: 420,
          borderRadius: 4,
          border: '1px solid #e5e7eb',
        }}
      >

        <CardContent>

          <Typography
            variant="h4"
            fontWeight={700}
            textAlign="center"
            sx={{ mb: 1 }}
          >
            Trust AWAKEN
          </Typography>

          <Typography
            textAlign="center"
            color="text.secondary"
            sx={{ mb: 4 }}
          >
            Sign in to continue
          </Typography>

          {error && (

            <Alert
              severity="error"
              sx={{ mb: 3 }}
            >
              {error}
            </Alert>

          )}

          <Stack spacing={3}>

            <TextField
              label="Email"
              value={email}
              onChange={(e) =>
                setEmail(e.target.value)
              }
            />

            <TextField
              label="Password"
              type="password"
              value={password}
              onChange={(e) =>
                setPassword(e.target.value)
              }
            />

            <Button
              variant="contained"
              size="large"
              onClick={submit}
              disabled={mutation.isPending}
            >
              {mutation.isPending
                ? 'Signing In...'
                : 'Sign In'}
            </Button>

          </Stack>

        </CardContent>

      </Card>

    </Box>

  )

}