import {
  Box,
  Stack,
  Typography,
} from '@mui/material'

import Studio from './components/fabric/FabricStudio'

export default function DocumentStudioPage() {
  return (
    <Box
      sx={{
        height: 'calc(100vh - 90px)',
        display: 'flex',
        flexDirection: 'column',
        minHeight: 0,
      }}
    >
      <Stack
        direction="row"
        justifyContent="space-between"
        alignItems="center"
        sx={{
          mb: 2,
          flexShrink: 0,
        }}
      >
        <Box>
          <Typography
            variant="h4"
            fontWeight={700}
          >
            Document Studio
          </Typography>

          <Typography color="text.secondary">
            Design certificates, badges, ID cards and printable documents.
          </Typography>
        </Box>
      </Stack>

      <Box
        sx={{
          flex: 1,
          minHeight: 0,
          overflow: 'hidden',
          border: '1px solid',
          borderColor: 'divider',
          borderRadius: 2,
          bgcolor: '#ffffff',
        }}
      >
        <Studio />
      </Box>
    </Box>
  )
}