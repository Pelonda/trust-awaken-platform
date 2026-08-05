import { DataGrid, GridColDef } from '@mui/x-data-grid'
import { Box, IconButton } from '@mui/material'
import EditIcon from '@mui/icons-material/Edit'
import DeleteIcon from '@mui/icons-material/Delete'

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
    ...columns,
    {
      field: 'actions',
      headerName: 'Actions',
      width: 120,
      sortable: false,
      renderCell: (params) => (
        <>
          <IconButton
            color="primary"
            onClick={() => onEdit?.(params.row)}
          >
            <EditIcon />
          </IconButton>

          <IconButton
            color="error"
            onClick={() => onDelete?.(params.row)}
          >
            <DeleteIcon />
          </IconButton>
        </>
      ),
    },
  ]

  return (
    <Box
      sx={{
        height: 600,
        width: '100%',
      }}
    >
      <DataGrid
        rows={rows}
        columns={finalColumns}
        getRowId={(row) => row.uuid}
        pageSizeOptions={[10, 25, 50]}
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