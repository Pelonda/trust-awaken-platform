import {
  Stack,
  TextField,
} from '@mui/material'

interface SessionFormData {
  session_code: string
  title: string
  session_number: number
}

interface Props {
  value: SessionFormData
  onChange: (value: SessionFormData) => void
}

export default function SessionForm({
  value,
  onChange,
}: Props) {
  return (
    <Stack spacing={3}>

      <TextField
        label="Session Code"
        value={value.session_code}
        onChange={(e) =>
          onChange({
            ...value,
            session_code: e.target.value,
          })
        }
      />

      <TextField
        label="Title"
        value={value.title}
        onChange={(e) =>
          onChange({
            ...value,
            title: e.target.value,
          })
        }
      />

      <TextField
        type="number"
        label="Session Number"
        value={value.session_number}
        onChange={(e) =>
          onChange({
            ...value,
            session_number: Number(e.target.value),
          })
        }
      />

    </Stack>
  )
}