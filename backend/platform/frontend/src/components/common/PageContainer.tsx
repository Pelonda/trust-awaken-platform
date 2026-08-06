import {
  Box,
  Button,
  Divider,
  Stack,
  Typography,
} from '@mui/material'

import AddIcon from '@mui/icons-material/Add'

import type { ReactNode } from 'react'

interface Props {
  title: string
  subtitle?: string
  buttonText?: string
  onAdd?: () => void
  children: ReactNode
}

export default function PageContainer({
  title,
  subtitle,
  buttonText,
  onAdd,
  children,
}: Props) {
  return (
    <Box>

      <Stack
        direction="row"
        justifyContent="space-between"
        alignItems="flex-start"
        sx={{ mb: 3 }}
      >

        <Box>

          <Typography
            variant="h4"
            fontWeight={700}
          >
            {title}
          </Typography>

          {subtitle && (
            <Typography
              color="text.secondary"
              sx={{ mt: 0.5 }}
            >
              {subtitle}
            </Typography>
          )}

        </Box>

        {buttonText && (
          <Button
            variant="contained"
            startIcon={<AddIcon />}
            onClick={onAdd}
          >
            {buttonText}
          </Button>
        )}

      </Stack>

      <Divider sx={{ mb: 3 }} />

      {children}

    </Box>
  )
}