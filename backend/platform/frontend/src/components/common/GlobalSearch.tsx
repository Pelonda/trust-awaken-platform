import {
  InputAdornment,
  Paper,
  TextField,
} from '@mui/material'

import SearchIcon from '@mui/icons-material/Search'

interface Props {
  value: string
  onChange: (value: string) => void
}

export default function GlobalSearch({
  value,
  onChange,
}: Props) {
  return (
    <Paper
      elevation={0}
      sx={{
        width: 350,
        borderRadius: 3,
        border: '1px solid #e5e7eb',
      }}
    >
      <TextField
        fullWidth
        placeholder="Search..."
        variant="standard"
        value={value}
        onChange={(e) =>
          onChange(e.target.value)
        }
        InputProps={{
          disableUnderline: true,
          startAdornment: (
            <InputAdornment position="start">
              <SearchIcon />
            </InputAdornment>
          ),
        }}
        sx={{
          px: 2,
          py: 1,
        }}
      />
    </Paper>
  )
}