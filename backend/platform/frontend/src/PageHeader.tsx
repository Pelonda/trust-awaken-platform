import {
  Button,
  Stack,
  Typography,
} from '@mui/material'

interface Props {
  title: string
  button?: string
  onClick?: () => void
}

export default function PageHeader({
  title,
  button,
  onClick,
}: Props) {
  return (
    <Stack
      direction="row"
      justifyContent="space-between"
      sx={{ mb: 3 }}
    >
      <Typography variant="h4">
        {title}
      </Typography>

      {button && (
        <Button
          variant="contained"
          onClick={onClick}
        >
          {button}
        </Button>
      )}
    </Stack>
  )
}