import {
  DataGrid,
  type GridColDef,
  type GridRenderCellParams,
} from '@mui/x-data-grid'

import {
  Box,
  IconButton,
  Tooltip,
} from '@mui/material'

import EditIcon from '@mui/icons-material/Edit'
import DeleteIcon from '@mui/icons-material/Delete'
import VisibilityIcon from '@mui/icons-material/Visibility'
import DownloadIcon from '@mui/icons-material/Download'

import StatusChip from '../common/StatusChip'

interface Props {
  rows: any[]
  columns: GridColDef[]
  onEdit?: (row: any) => void
  onDelete?: (row: any) => void
  onPreview?: (row: any) => void
  onDownload?: (row: any) => void
}

export default function AppDataGrid({
  rows,
  columns,
  onEdit,
  onDelete,
  onPreview,
  onDownload,
}: Props) {

  const finalColumns: GridColDef[] = [

    ...columns.map((column) => ({

      ...column,

      renderCell:

        column.field === 'status'

          ? (params: GridRenderCellParams) => (

              <StatusChip
                value={String(params.value)}
              />

            )

          : column.renderCell,

    })),

    {

      field: 'actions',

      headerName: 'Actions',

      width: 190,

      sortable: false,

      renderCell: (params) => (

        <>

          <Tooltip title="Preview">

            <IconButton
              onClick={() =>
                onPreview?.(params.row)
              }
            >
              <VisibilityIcon fontSize="small" />
            </IconButton>

          </Tooltip>

          <Tooltip title="Download">

            <IconButton
              onClick={() =>
                onDownload?.(params.row)
              }
            >
              <DownloadIcon fontSize="small" />
            </IconButton>

          </Tooltip>

          <Tooltip title="Edit">

            <IconButton
              color="primary"
              onClick={() =>
                onEdit?.(params.row)
              }
            >
              <EditIcon fontSize="small" />
            </IconButton>

          </Tooltip>

          <Tooltip title="Delete">

            <IconButton
              color="error"
              onClick={() =>
                onDelete?.(params.row)
              }
            >
              <DeleteIcon fontSize="small" />
            </IconButton>

          </Tooltip>

        </>

      ),

    },

  ]

  return (

    <Box
      sx={{
        height: 620,
        width: '100%',
      }}
    >

      <DataGrid
        rows={rows}
        columns={finalColumns}
        getRowId={(row) => row.uuid}
        pageSizeOptions={[10, 25, 50]}
        disableRowSelectionOnClick
        initialState={{
          pagination: {
            paginationModel: {
              pageSize: 10,
            },
          },
        }}
      />

    </Box>

  )

}