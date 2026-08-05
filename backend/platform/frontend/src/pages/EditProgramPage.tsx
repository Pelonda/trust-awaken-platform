import {
  Button,
  CircularProgress,
  Paper,
  Stack,
  TextField,
  Typography,
} from '@mui/material'

import { useEffect, useState } from 'react'
import { useNavigate, useParams } from 'react-router-dom'
import { useMutation, useQuery } from '@tanstack/react-query'

import { getProgram, updateProgram } from '../api/programs'

export default function EditProgramPage() {
  const navigate = useNavigate()
  const { uuid } = useParams()

  const { data, isLoading } = useQuery({
    queryKey: ['program', uuid],
    queryFn: () => getProgram(uuid!),
    enabled: !!uuid,
  })

  const mutation = useMutation({
    mutationFn: (payload: any) => updateProgram(uuid!, payload),
    onSuccess: () => {
      navigate('/programs')
    },
  })

  const [form, setForm] = useState({
    program_code: '',
    title: '',
    program_type: '',
    delivery_mode: '',
    status: 'draft',
    language: 'en',
    credential_enabled: true,
  })

  useEffect(() => {
    if (!data) return

    setForm({
      program_code: data.program_code ?? '',
      title: data.title ?? '',
      program_type: data.program_type ?? '',
      delivery_mode: data.delivery_mode ?? '',
      status: data.status ?? 'draft',
      language: data.language ?? 'en',
      credential_enabled: data.credential_enabled ?? true,
    })
  }, [data])

  if (isLoading) {
    return <CircularProgress />
  }

  return (
    <Paper sx={{ p: 4, maxWidth: 700 }}>
      <Typography
        variant="h4"
        sx={{ mb: 3 }}
      >
        Edit Program
      </Typography>

      <Stack spacing={3}>
        <TextField
          label="Program Code"
          value={form.program_code}
          onChange={(e) =>
            setForm({ ...form, program_code: e.target.value })
          }
        />

        <TextField
          label="Title"
          value={form.title}
          onChange={(e) =>
            setForm({ ...form, title: e.target.value })
          }
        />

        <TextField
          label="Program Type"
          value={form.program_type}
          onChange={(e) =>
            setForm({ ...form, program_type: e.target.value })
          }
        />

        <TextField
          label="Delivery Mode"
          value={form.delivery_mode}
          onChange={(e) =>
            setForm({ ...form, delivery_mode: e.target.value })
          }
        />

        <Button
          variant="contained"
          onClick={() =>
            mutation.mutate({
              ...form,
              organization_id: 1,
            })
          }
          disabled={mutation.isPending}
        >
          {mutation.isPending ? 'Saving...' : 'Update Program'}
        </Button>
      </Stack>
    </Paper>
  )
}