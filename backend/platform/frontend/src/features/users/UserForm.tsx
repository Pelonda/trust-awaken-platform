import {
  MenuItem,
  Stack,
  TextField,
} from '@mui/material'

interface UserFormData {
  name: string
  email: string
  password: string
  phone: string
  job_title: string
  user_type: string
  status: string
}

interface Props {
  value: UserFormData
  onChange: (value: UserFormData) => void
}

export default function UserForm({
  value,
  onChange,
}: Props) {
  return (
    <Stack spacing={3}>

      <TextField
        label="Full Name"
        value={value.name}
        onChange={(e) =>
          onChange({
            ...value,
            name: e.target.value,
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

      <TextField
        label="Password"
        type="password"
        value={value.password}
        onChange={(e) =>
          onChange({
            ...value,
            password: e.target.value,
          })
        }
      />

      <TextField
        label="Phone"
        value={value.phone}
        onChange={(e) =>
          onChange({
            ...value,
            phone: e.target.value,
          })
        }
      />

      <TextField
        label="Job Title"
        value={value.job_title}
        onChange={(e) =>
          onChange({
            ...value,
            job_title: e.target.value,
          })
        }
      />

      <TextField
        select
        label="User Type"
        value={value.user_type}
        onChange={(e) =>
          onChange({
            ...value,
            user_type: e.target.value,
          })
        }
      >
        <MenuItem value="organization">
          Organization
        </MenuItem>

        <MenuItem value="system">
          System
        </MenuItem>
      </TextField>

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
        <MenuItem value="active">
          Active
        </MenuItem>

        <MenuItem value="inactive">
          Inactive
        </MenuItem>
      </TextField>

    </Stack>
  )
}