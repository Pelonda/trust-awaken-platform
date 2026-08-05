import {
  Stack,
  TextField,
} from '@mui/material'

interface ParticipantFormData {
  participant_code: string
  first_name: string
  last_name: string
  email: string
}

interface Props {
  value: ParticipantFormData
  onChange: (value: ParticipantFormData) => void
}

export default function ParticipantForm({
  value,
  onChange,
}: Props) {
  return (
    <Stack spacing={3}>

      <TextField
        label="Participant Code"
        value={value.participant_code}
        onChange={(e) =>
          onChange({
            ...value,
            participant_code: e.target.value,
          })
        }
      />

      <TextField
        label="First Name"
        value={value.first_name}
        onChange={(e) =>
          onChange({
            ...value,
            first_name: e.target.value,
          })
        }
      />

      <TextField
        label="Last Name"
        value={value.last_name}
        onChange={(e) =>
          onChange({
            ...value,
            last_name: e.target.value,
          })
        }
      />

      <TextField
        label="Email"
        value={value.email}
        onChange={(e) =>
          onChange({
            ...value,
            email: e.target.value,
          })
        }
      />

    </Stack>
  )
}