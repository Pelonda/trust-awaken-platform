import { TextField } from '@mui/material'

interface Props {
  value: string
  onChange: (value: string) => void
}

export default function SearchBar({
  value,
  onChange,
}: Props) {
  return (
    <TextField
      fullWidth
      label="Search..."
      value={value}
      onChange={(e) => onChange(e.target.value)}
      sx={{ mb: 3 }}
    />
  )
}