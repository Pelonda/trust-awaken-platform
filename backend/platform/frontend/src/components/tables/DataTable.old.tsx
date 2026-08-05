import { Box, IconButton } from '@mui/material'
import {
  DataGrid,
  type GridColDef,
  type GridRenderCellParams,
} from '@mui/x-data-grid'

import EditIcon from '@mui/icons-material/Edit'
import DeleteIcon from '@mui/icons-material/Delete'

import StatusChip from '../common/StatusChip'

interface Props {
  columns: GridColDef[]
  rows: any[]
  onEdit?: (row: any) => void
  onDelete?: (row: any) => void
}

export default function DataTable({
  columns,
  rows,
  onEdit,
  onDelete,
}: Props) {
  const finalColumns: GridColDef[] = [
    ...columns.map((column) => ({
      ...column,
      renderCell:
        column.field === 'status'
          ? (params: GridRenderCellParams) => (
              <StatusChip value={String(params.value)} />
            )
          : column.field === 'delivery_mode'
          ? (params: GridRenderCellParams) => (
              <span>
                {String(params.value)
                  .replace('_', ' ')
                  .replace(/\b\w/g, (c) => c.toUpperCase())}
              </span>
            )
          : column.renderCell,
    })),

    {
      field: 'actions',
      headerName: 'Actions',
      width: 120,
      sortable: false,
      filterable: false,
      disableColumnMenu: true,
      align: 'center',
      headerAlign: 'center',

      renderCell: (params: GridRenderCellParams) => (
        <>
          <IconButton
            color="primary"
            size="small"
            onClick={() => onEdit?.(params.row)}
          >
            <EditIcon fontSize="small" />
          </IconButton>

          <IconButton
            color="error"
            size="small"
            onClick={() => onDelete?.(params.row)}
          >
            <DeleteIcon fontSize="small" />
          </IconButton>
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
        sx={{
          border: 0,

          '& .MuiDataGrid-columnHeaders': {
            backgroundColor: '#f8fafc',
            fontWeight: 700,
          },

          '& .MuiDataGrid-cell': {
            borderColor: '#f1f5f9',
          },

          '& .MuiDataGrid-row:hover': {
            backgroundColor: '#f8fafc',
          },
        }}
      />
    </Box>
  )
}