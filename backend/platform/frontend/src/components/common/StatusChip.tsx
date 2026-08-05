import Chip from '@mui/material/Chip'

interface Props {
  value: string
}

export default function StatusChip({
  value,
}: Props) {

  const color =
    value === 'active'
      ? 'success'
      : value === 'draft'
      ? 'warning'
      : 'default'

  return (
    <Chip
      label={value}
      color={color}
      size="small"
      sx={{
        textTransform: 'capitalize',
        fontWeight: 600,
      }}
    />
  )
}