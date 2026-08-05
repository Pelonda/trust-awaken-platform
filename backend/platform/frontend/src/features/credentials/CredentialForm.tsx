import {
  MenuItem,
  Stack,
  TextField,
} from '@mui/material'

interface CredentialFormData {
  participant_id: string
  program_id: string
  credential_type: string
}

interface Props {
  value: CredentialFormData
  onChange: (value: CredentialFormData) => void
}

export default function CredentialForm({
  value,
  onChange,
}: Props) {
  return (
    <Stack spacing={3}>

      <TextField
        label="Participant ID"
        value={value.participant_id}
        onChange={(e) =>
          onChange({
            ...value,
            participant_id: e.target.value,
          })
        }
      />

      <TextField
        label="Program ID"
        value={value.program_id}
        onChange={(e) =>
          onChange({
            ...value,
            program_id: e.target.value,
          })
        }
      />

      <TextField
        select
        label="Credential Type"
        value={value.credential_type}
        onChange={(e) =>
          onChange({
            ...value,
            credential_type: e.target.value,
          })
        }
      >
        <MenuItem value="certificate">
          Certificate
        </MenuItem>

        <MenuItem value="badge">
          Badge
        </MenuItem>

      </TextField>

    </Stack>
  )
}