import {
  Box,
  Grid,
  Paper,
  Typography,
} from '@mui/material'

import CanvasEditor from './CanvasEditor'
import LayersPanel from './LayersPanel'
import PropertiesPanel from './PropertiesPanel'
import AssetLibrary from './AssetLibrary'

export default function DocumentStudioPage() {
  return (
    <Box>

      <Typography
        variant="h4"
        fontWeight={700}
        sx={{ mb: 3 }}
      >
        Document Studio
      </Typography>

      <Grid
        container
        spacing={2}
      >

        <Grid
          size={{ xs: 12, md: 2 }}
        >
          <Paper
            sx={{
              height: 800,
              p: 2,
            }}
          >
            <AssetLibrary />
          </Paper>
        </Grid>

        <Grid
          size={{ xs: 12, md: 7 }}
        >
          <Paper
            sx={{
              height: 800,
              p: 2,
            }}
          >
            <CanvasEditor />
          </Paper>
        </Grid>

        <Grid
          size={{ xs: 12, md: 3 }}
        >
          <Paper
            sx={{
              height: 390,
              mb: 2,
              p: 2,
            }}
          >
            <LayersPanel />
          </Paper>

          <Paper
            sx={{
              height: 390,
              p: 2,
            }}
          >
            <PropertiesPanel />
          </Paper>
        </Grid>

      </Grid>

    </Box>
  )
}