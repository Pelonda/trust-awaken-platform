import {
  Button,
  Paper,
  Stack,
  TextField,
  Typography,
} from '@mui/material'

import { useState } from 'react'
import { useNavigate } from 'react-router-dom'

import { useCreateProgram } from '../hooks/useCreateProgram'

export default function CreateProgramPage() {

  const navigate = useNavigate()

  const mutation = useCreateProgram()

  const [programCode, setProgramCode] = useState('')
  const [title, setTitle] = useState('')
  const [programType, setProgramType] = useState('')
  const [deliveryMode, setDeliveryMode] = useState('')

  function submit() {

    mutation.mutate(
      {
        organization_id: 1,
        program_code: programCode,
        title,
        program_type: programType,
        delivery_mode: deliveryMode,

        // Required backend fields
        status: 'draft',
        language: 'en',
        credential_enabled: true,
      },
      {
        onSuccess: () => {
          navigate('/programs')
        },
        onError: (error: any) => {
          console.log(error.response?.data)
        },
      }
    )

  }

  return (
    <Paper sx={{ p: 4, maxWidth: 700 }}>

      <Typography
        variant="h4"
        sx={{ mb: 3 }}
      >
        Create Program
      </Typography>

      <Stack spacing={3}>

        <TextField
          label="Program Code"
          value={programCode}
          onChange={(e) => setProgramCode(e.target.value)}
        />

        <TextField
          label="Title"
          value={title}
          onChange={(e) => setTitle(e.target.value)}
        />

        <TextField
          label="Program Type"
          value={programType}
          onChange={(e) => setProgramType(e.target.value)}
        />

        <TextField
          label="Delivery Mode"
          value={deliveryMode}
          onChange={(e) => setDeliveryMode(e.target.value)}
        />

        <Button
          variant="contained"
          onClick={submit}
          disabled={mutation.isPending}
        >
          Save Program
        </Button>

      </Stack>

    </Paper>
  )
}