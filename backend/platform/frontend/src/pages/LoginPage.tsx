import {
  Button,
  Stack,
  TextField,
  Typography,
} from '@mui/material'

import { useNavigate } from 'react-router-dom'

import AuthLayout from '../components/layouts/AuthLayout'

export default function LoginPage() {
  const navigate = useNavigate()

  return (
    <AuthLayout>
      <Stack spacing={3}>

        <Typography
          variant="h4"
          fontWeight="bold"
        >
          Trust AWAKEN
        </Typography>

        <TextField
          label="Email"
          fullWidth
        />

        <TextField
          label="Password"
          type="password"
          fullWidth
        />

        <Button
          variant="contained"
          size="large"
          onClick={() => navigate('/dashboard')}
        >
          Sign In
        </Button>

      </Stack>
    </AuthLayout>
  )
}