import {
  MenuItem,
  Stack,
  TextField,
} from '@mui/material'

interface AttendanceFormData {
  participant_id: string
  session_id: string
  status: string
}

interface Props {
  value: AttendanceFormData
  onChange: (value: AttendanceFormData) => void
}

export default function AttendanceForm({
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
        label="Session ID"
        value={value.session_id}
        onChange={(e) =>
          onChange({
            ...value,
            session_id: e.target.value,
          })
        }
      />

      <TextField
        select
        label="Status"
        value={value.status}
        onChange={(e) =>
          onChange({
            ...value,
            status: e.target.value,
          })
        }
      >
        <MenuItem value="present">
          Present
        </MenuItem>

        <MenuItem value="late">
          Late
        </MenuItem>

        <MenuItem value="absent">
          Absent
        </MenuItem>

      </TextField>

    </Stack>
  )
}