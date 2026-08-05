import {
  IconButton,
  Stack,
  Tooltip,
} from '@mui/material'

import RefreshIcon from '@mui/icons-material/Refresh'
import FileDownloadIcon from '@mui/icons-material/FileDownload'
import FilterAltIcon from '@mui/icons-material/FilterAlt'

interface Props {
  onRefresh?: () => void
  onExport?: () => void
  onFilter?: () => void
}

export default function AppToolbarActions({
  onRefresh,
  onExport,
  onFilter,
}: Props) {
  return (
    <Stack
      direction="row"
      spacing={1}
    >
      <Tooltip title="Refresh">

        <IconButton onClick={onRefresh}>
          <RefreshIcon />
        </IconButton>

      </Tooltip>

      <Tooltip title="Filter">

        <IconButton onClick={onFilter}>
          <FilterAltIcon />
        </IconButton>

      </Tooltip>

      <Tooltip title="Export">

        <IconButton onClick={onExport}>
          <FileDownloadIcon />
        </IconButton>

      </Tooltip>

    </Stack>
  )
}