import {
  Stack,
  TextField,
} from '@mui/material'

interface ProgramFormData {
  program_code: string
  title: string
  program_type: string
  delivery_mode: string
}

interface Props {
  value: ProgramFormData
  onChange: (value: ProgramFormData) => void
}

export default function ProgramForm({
  value,
  onChange,
}: Props) {

  return (

    <Stack spacing={3}>

      <TextField
        label="Program Code"
        value={value.program_code}
        onChange={(e) =>
          onChange({
            ...value,
            program_code: e.target.value,
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
        label="Program Type"
        value={value.program_type}
        onChange={(e) =>
          onChange({
            ...value,
            program_type: e.target.value,
          })
        }
      />

      <TextField
        label="Delivery Mode"
        value={value.delivery_mode}
        onChange={(e) =>
          onChange({
            ...value,
            delivery_mode: e.target.value,
          })
        }
      />

    </Stack>

  )

}