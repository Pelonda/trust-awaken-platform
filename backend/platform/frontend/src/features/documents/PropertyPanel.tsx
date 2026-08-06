import {
  Stack,
  TextField,
  Typography,
} from '@mui/material'

export default function PropertiesPanel() {
  return (
    <>
      <Typography
        variant="h6"
        fontWeight={700}
        sx={{ mb: 2 }}
      >
        Properties
      </Typography>

      <Stack spacing={2}>

        <TextField
          label="X"
          size="small"
        />

        <TextField
          label="Y"
          size="small"
        />

        <TextField
          label="Width"
          size="small"
        />

        <TextField
          label="Height"
          size="small"
        />

      </Stack>
    </>
  )
}