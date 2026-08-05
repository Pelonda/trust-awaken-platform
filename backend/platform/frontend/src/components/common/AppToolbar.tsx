import {
  Box,
  Button,
  Stack,
  Typography,
} from '@mui/material'

import AddIcon from '@mui/icons-material/Add'

import AppToolbarActions from './AppToolbarActions'

interface Props {
  title: string
  subtitle?: string
  buttonText?: string
  onAdd?: () => void
  onRefresh?: () => void
  onExport?: () => void
  onFilter?: () => void
  children?: React.ReactNode
}

export default function AppToolbar({
  title,
  subtitle,
  buttonText,
  onAdd,
  onRefresh,
  onExport,
  onFilter,
  children,
}: Props) {
  return (
    <Box sx={{ mb: 3 }}>

      <Stack
        direction="row"
        justifyContent="space-between"
        alignItems="center"
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
              sx={{ mt: .5 }}
            >
              {subtitle}
            </Typography>

          )}

        </Box>

        <Stack
          direction="row"
          spacing={2}
        >

          <AppToolbarActions
            onRefresh={onRefresh}
            onExport={onExport}
            onFilter={onFilter}
          />

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

      </Stack>

      <Box sx={{ mt: 3 }}>
        {children}
      </Box>

    </Box>
  )
}