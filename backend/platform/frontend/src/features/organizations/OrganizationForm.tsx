import {
  MenuItem,
  Stack,
  TextField,
} from '@mui/material'

interface OrganizationFormData {
  display_name: string
  legal_name: string
  organization_type: string
}

interface Props {
  value: OrganizationFormData
  onChange: (value: OrganizationFormData) => void
}

export default function OrganizationForm({
  value,
  onChange,
}: Props) {
  return (
    <Stack spacing={3}>

      <TextField
        label="Display Name"
        value={value.display_name}
        onChange={(e) =>
          onChange({
            ...value,
            display_name: e.target.value,
          })
        }
      />

      <TextField
        label="Legal Name"
        value={value.legal_name}
        onChange={(e) =>
          onChange({
            ...value,
            legal_name: e.target.value,
          })
        }
      />

      <TextField
        select
        label="Organization Type"
        value={value.organization_type}
        onChange={(e) =>
          onChange({
            ...value,
            organization_type: e.target.value,
          })
        }
      >
        <MenuItem value="nonprofit">
          Non-Profit
        </MenuItem>

        <MenuItem value="company">
          Company
        </MenuItem>

        <MenuItem value="government">
          Government
        </MenuItem>

        <MenuItem value="education">
          Education
        </MenuItem>

      </TextField>

    </Stack>
  )
}