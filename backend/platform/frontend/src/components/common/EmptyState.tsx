import {
  Box,
  Button,
  Typography,
} from '@mui/material'

import InboxIcon from '@mui/icons-material/Inbox'

interface Props {
  title: string
  subtitle: string
  button?: string
  onClick?: () => void
}

export default function EmptyState({
  title,
  subtitle,
  button,
  onClick,
}: Props) {
  return (
    <Box
      sx={{
        py: 10,
        textAlign: 'center',
      }}
    >
      <InboxIcon
        sx={{
          fontSize: 72,
          color: 'text.disabled',
          mb: 2,
        }}
      />

      <Typography
        variant="h5"
        fontWeight={700}
      >
        {title}
      </Typography>

      <Typography
        color="text.secondary"
        sx={{ mt: 1, mb: 3 }}
      >
        {subtitle}
      </Typography>

      {button && (
        <Button
          variant="contained"
          onClick={onClick}
        >
          {button}
        </Button>
      )}
    </Box>
  )
}