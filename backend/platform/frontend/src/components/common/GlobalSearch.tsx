import {
  InputAdornment,
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
    <TextField
      size="small"
      placeholder="Search..."
      value={value}
      onChange={(e) =>
        onChange(e.target.value)
      }
      sx={{
        width: 320,
      }}
      slotProps={{
        input: {
          startAdornment: (
            <InputAdornment position="start">
              <SearchIcon />
            </InputAdornment>
          ),
        },
      }}
    />
  )
}